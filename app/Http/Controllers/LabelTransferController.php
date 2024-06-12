<?php

namespace App\Http\Controllers;

use App\Helpers\UtilsHelper;
use App\Models\BranchLocation;
use App\Models\LabelTransfer;
use App\Models\Product;
use App\Models\TransferReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LabelExport;

class LabelTransferController extends Controller
{

    public function index(Request $request)
    {

        $user_id = auth()->user()->id;
        $role_id = auth()->user()->role_id;

        $search = $request->input('search');
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $sort = $request->input('sort') ?? 't.created_at|desc';
        if ($sort)
            $sort = explode('|', $sort);

        $status = LabelTransfer::STATUS;

        $data = DB::table('label_transfers as t')
            ->join('branch_locations as s', 's.id', '=', 't.source_id')
            ->join('branch_locations as d', 'd.id', '=', 't.destination_id')
            ->select('t.id', 't.transfer_no', 't.date', 't.status', 's.location as source', 'd.location as destination')
            ->where(function ($query) use ($search) {
                $query->orWhere('t.transfer_no', 'like', "%$search%")
                    ->orWhere('s.location', 'like', "%$search%")
                    ->orWhere('d.location', 'like', "%$search%");
            });

        $data = $data->orderBy($sort[0], $sort[1])
            ->paginate($per_page, ['*'], 'page', $page);
        $total = $data->total();

        if ($request->ajax()) {
            return [
                'table' => view('pages.label.transfer.table', compact('data', 'status'))->render(),
                'pagination' => view('components.pagination', compact('data'))->render(),
                'total' => $total,
            ];
        }

        return view('pages.label.transfer.index', compact('data', 'status'));
    }

    public function show(Request $request)
    {
        $id = $request->id;
        $result = $this->getLabelTransferMaster();
        $branches = $result['branches'];
        $products = $result['products'];
        $reasons = $result['reasons'];

        $transfer = null;
        $disabled = false;

        if (is_numeric($id)) {
            $transfer = LabelTransfer::find($id);
            if (!$transfer)
                return redirect()->route('label-transfer.index')->with('error', 'Invalid label id');
            $transfer_no = $transfer->transfer_no;
            if ($transfer->status != LabelTransfer::STATUS['ordered']['id'])
                $disabled = true;
        } else {
            $transfer_no = UtilsHelper::getMaxRequestNo('as_label_transfers');
        }

        return view('pages.label.transfer.action', compact('reasons', 'branches', 'products', 'transfer', 'transfer_no', 'disabled'));
    }

    public function showSummary(Request $request)
    {
        $id = $request->id;
        $label = LabelTransfer::find($id);
        if (!$label)
            return redirect()->route('label-request.index')->with('error', 'Invalid label id');
        $status = LabelTransfer::STATUS;
        return view('pages.label.summary', compact('label', 'status'));
    }


    public function save(Request $request)
    {

        try {
            $validator = $request->validate([
                'transfer_no' => 'nullable|string|max:50',
                'date' => 'required|date_format:Y-m-d',
                'source_id' => 'required|integer|exists:branch_locations,id',
                'destination_id' => 'required|integer|exists:branch_locations,id',
                'reason_id' => 'required|integer|exists:transfer_reasons,id',
                'reason_others' => 'nullable|string|max:500',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.qty' => 'required|integer|min:1',
            ]);

            $route_name = $request->route()->getName();

            $message = "STO created successfully";


            DB::beginTransaction();

            if ($route_name == 'label-transfer.save') {
                $no = UtilsHelper::getMaxRequestNo('as_label_transfers');
                if ($no != $validator['transfer_no'])
                    return $this->returnError('invalid-transfer-no', [
                        'transfer_no' => $no,
                        'msg' => 'Your request id is already used by another user New Id: ' . $no
                    ]);
                $transfer = new LabelTransfer();
                $transfer->transfer_no = $no;
                $transfer->created_by = auth()->user()->id;
            } else {
                $transfer = LabelTransfer::find($request->id);
                $transfer->updated_by = auth()->user()->id;
                if ($transfer->status != LabelTransfer::STATUS['ordered']['id'])
                    return $this->returnError('invalid-status', 'STO is already processed');

                $message = "STO updated successfully";
            }

            $transfer->date = $validator['date'];
            $transfer->source_id = $validator['source_id'];
            $transfer->destination_id = $validator['destination_id'];
            $transfer->reason_id = $validator['reason_id'];
            $transfer->reason_others = $validator['reason_others'];

            $transfer->save();

            $items = $validator['items'];
            $transfer->details()->delete();
            foreach ($items as $item) {
                $transfer->details()->create($item);
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess($transfer->id, $message);

    }

    public function export(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            $role_id = auth()->user()->role_id;
            $user_location_id = auth()->user()->branch_office_id;
            $search = $request->input('search');
            $route_name = $request->route()->getName();

            $query = DB::table('labels as l')
                ->join('users as u', 'l.agent_id', '=', 'u.id')
                ->select('l.id', 'l.request_id', 'l.request_date', 'u.name', 'l.status')
                ->where(function ($query) use ($search) {
                    $query->orWhere('l.request_id', 'like', "%$search%")->orWhere('u.name', 'like', "%$search%");
                });

            if ($route_name == 'label-return.export') {
                $query = $query->where('l.status', '!=', Label::STATUS['requested']['id']);
            }

            if ($role_id != UtilsHelper::SUPER_ADMIN_ROLE) {
                $query = $query->where('l.location_id', $user_location_id);
            }

            $label = $query->get();

            return Excel::download(new LabelExport($label), 'label.xlsx');
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    private function getLabelTransferMaster()
    {
        $branches = BranchLocation::where('status', 1)->orderBy('location')->get(['id', 'location as name'])->toArray();
        $products = Product::where('status', 1)->orderBy('name')->get(['id', 'name'])->toArray();
        $reasons = TransferReason::where('status', 1)->orderBy('name')->get(['id', 'name'])->toArray();
        return compact('branches', 'products', 'reasons');
    }

}

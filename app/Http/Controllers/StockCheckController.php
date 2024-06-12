<?php

namespace App\Http\Controllers;

use App\Helpers\UtilsHelper;
use App\Models\BranchLocation;
use App\Models\GrnProductDetail;
use App\Models\Label;
use App\Models\LabelProductDetail;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockByAgent;
use App\Models\StockCheck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\StockExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockCheckExport;

class StockCheckController extends Controller
{

    public function index(Request $request)
    {

        $user_id = auth()->user()->id;
        $role_id = auth()->user()->role_id;
        $user_location_id = auth()->user()->branch_office_id;


        $search = $request->input('search');
        $search_column = $request->input('search_column');
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $sort = $request->input('sort') ?? 's.created_at|desc';
        if ($sort)
            $sort = explode('|', $sort);

        $status = StockCheck::STATUS;

        $data = DB::table('stock_checks as s')
            ->join('branch_locations as l', 's.location_id', '=', 'l.id')
            ->select('s.id', 's.request_id', 'l.location as name', 's.type', 's.date', 's.status', 's.agent_id')
            ->where(function ($query) use ($search) {
                $query->orWhere('s.request_id', 'like', "%$search%")->orWhere('l.location', 'like', "%$search%");
            });

        if($role_id != UtilsHelper::SUPER_ADMIN_ROLE){
            $data = $data->where('s.location_id', $user_location_id);
        }

        $data = $data->orderBy($sort[0], $sort[1])
            ->paginate($per_page, ['*'], 'page', $page);
        $total = $data->total();

        if ($request->ajax()) {
            return [
                'table' => view('pages.stock-check.table', compact('data', 'status'))->render(),
                'pagination' => view('components.pagination', compact('data'))->render(),
                'total' => $total,
            ];
        }

        return view('pages.stock-check.index', compact('data', 'status'));
    }

    public function show(Request $request)
    {
        $id = $request->id;

        $agents = $result['agents'] ?? [];

        $branch_where = ['status' => 1];

        if(auth()->user()->role_id != UtilsHelper::SUPER_ADMIN_ROLE) {
            $branch_where['id'] = auth()->user()->branch_office_id;
        }

        $products = Product::where('status', 1)->orderBy('name')->get(['id', 'name'])->toArray();
        $locations = BranchLocation::where($branch_where)
            ->orderBy('name')->get(['id', 'location as name'])->toArray();

        $check = null;
        $disabled = false;

        if (is_numeric($id)) {
            $check = StockCheck::find($id);
            if (!$check)
                return redirect()->route('stock-check.index')->with('error', 'Invalid Stock Check id');
            $request_id = $check->request_id;
            $disabled = true;

            $agents = User::where([
                'branch_office_id' => $check->location_id,
                'role_id' => UtilsHelper::FIELD_AGENT
            ])->orderBy('name')->get(['id', 'name'])->toArray();


        } else {
            $request_id = UtilsHelper::getMaxRequestNo('as_stock_checks');
        }
        $is_adjust = true;


        return view('pages.stock-check.action', compact('agents', 'products', 'locations', 'check', 'request_id', 'disabled', 'is_adjust'));
    }

    public function exportshow(Request $request)
    {
       
        $id = $request->query('id');
        $data = StockCheck::where('id', $id)->first();
        if (!$data) {
            abort(404, 'Data not found');
        }
        $export = new StockCheckExport($data);
        return Excel::download($export, 'stock-show.xlsx');
    }

    public function stocks(Request $request)
    {

        $agent_id = $request->input('agent_id');
        $location_id = $request->input('location_id');
        $product_id = $request->input('product_id');
        $type = $request->input('type');

        $data = [
            'stock' => 0,
            'location_id' => $location_id,
            'product_id' => $product_id
        ];
        try {
            $data['stock'] = $this->checkStockCount($type, $location_id, $product_id, $agent_id);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        return $this->returnSuccess($data);

    }

    public function save(Request $request)
    {
        try {

            $validator = $request->validate([
                'click_type' => 'nullable|string',
                'type' => 'required|string|in:location,agent',
                'request_id' => 'required|string|max:50',
                'location_id' => 'required|integer|exists:branch_locations,id',
                'agent_id' => 'nullable|required_if:type,agent|integer|exists:users,id',
                'items' => 'nullable|array|min:1',
                'items.*.product_id' => 'nullable|integer|exists:products,id',
                'details' => 'nullable|array|min:1',
                'details.*.product_id' => 'nullable|integer',
                'details.*.code' => 'nullable|string',
            ]);


            DB::beginTransaction();

            $click_type = $validator['click_type'];
            $route_name = $request->route()->getName();

            if ($route_name === 'stock-check.update') {
                $id = $request->route('id');
                $check = StockCheck::find($id);
                if (!$check)
                    return $this->returnError('Invalid Stock Check id');

                if ($click_type === 'enquiry-stop') {
                    $check->status = StockCheck::STATUS[$click_type]['id'];
                    $check->save();
                    DB::commit();
                    return $this->returnSuccess([
                       'id' => $check->id,
                        'status' => $check->status
                    ], 'Stock Check updated successfully');
                }

            } else {
                $no = UtilsHelper::getMaxRequestNo('as_stock_checks');
                if ($no != $validator['request_id'])
                    return $this->returnError('invalid-request-no', [
                        'request_id' => $no,
                        'msg' => 'Your request id is already used by another user New Id: ' . $no
                    ]);
                $check = new StockCheck();
                $check->request_id = $no;
                $check->date = now();
                $check->type = $validator['type'];
                $check->agent_id = $validator['agent_id'];
                $check->created_by = auth()->user()->id;
                $check->location_id = $validator['location_id'];
                $check->status = StockCheck::STATUS['enquiry']['id'];
            }

            if ($click_type === 'enquiry-start') {
                $check->status = StockCheck::STATUS[$click_type]['id'];
            }

            $check->save();

            $details = $validator['details'];
            $check->productDetails()->delete();
            foreach ($details as $detail) {
                $check->productDetails()->create($detail);
            }
            $stock_close = true;
            $items = $validator['items'];
            $check->products()->delete();
            foreach ($items as $item) {
                $detail = array_filter($details, function ($i) use ($item) {
                    return $i['product_id'] == $item['product_id'];
                });

                $item['qty'] = $this->checkStockCount($validator['type'], $validator['location_id'], $item['product_id'], $validator['agent_id']);

                if (count($detail) != $item['qty']) {
                    $stock_close = false;
                }
                $item['on_hand'] = count($detail);
                $check->products()->create($item);
            }

            if ($stock_close) {
                $check->status = StockCheck::STATUS['complete']['id'];
                $check->save();
            }

            if ($click_type === 'override' || $click_type === 'enquiry-adjust') {
                $check->status = StockCheck::STATUS[$click_type]['id'];
                $check->save();
                $result = $this->overrideStock($check->id);
                if (!$result['status'])
                    return $this->returnError($result['message']);
            }

            DB::commit();


        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess([
           'id' => $check->id,
            'status' => $check->status
        ], 'Stock Check created successfully');

    }

    public function verify(Request $request)
    {

        $location_id = $request->input('location_id');
        $agent_id = $request->input('agent_id');
        $code = $request->input('code');
        $product_id = $request->input('product_id');
        $type = $request->input('type');

        try {

            if ($type === 'location') {
                $label = GrnProductDetail::where([
                    'location_id' => $location_id,
                    'product_id' => $product_id,
                    'scanned_product_id' => $code,
                ])->orderByRaw('FIELD(status, "open") desc')
                    ->first();
                if (!$label)
                    return $this->returnError('Please scan valid label');

                if ($label->status !== GrnProductDetail::STATUS['open']['id'])
                    return $this->returnError('Label is already ' . ucfirst($label->status));

            } else {
                $label = LabelProductDetail::where([
                    'product_id' => $product_id,
                    'code' => $code,
                ])->join('labels as l', 'l.id', '=', 'label_product_details.label_id')
                    ->where('l.agent_id', $agent_id)
                    ->where('l.location_id', $location_id)
                    ->where('l.status', '!=', Label::STATUS['closed']['id'])
                    // ->where('label_product_details.status', LabelProductDetail::STATUS['issued']['id'])
                    ->select('label_product_details.id', 'label_product_details.status')
                    ->first();

                if (!$label)
                    return $this->returnError('Please scan valid label');

                if ($label->status !== LabelProductDetail::STATUS['issued']['id'])
                    return $this->returnError('Label is already ' . ucfirst($label->status));

            }

        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        return $this->returnSuccess([], 'Label is verified');
    }


    private function checkStockCount($type, $location_id, $product_id, $agent_id)
    {
        $stock_qty = 0;
        if ($type === 'location') {
            $stock = Stock::where(['location_id' => $location_id, 'product_id' => $product_id])->first();
            if ($stock) {
                // $stock_qty = $stock->stock - ($stock->adjust - $stock->damaged);
                $stock_qty = $stock->balance;
            }
        } else {
            $stock = StockByAgent::where(['location_id' => $location_id, 'agent_id' => $agent_id, 'product_id' => $product_id])->first();
            if ($stock) {
                $stock_qty = $stock->balance;
            }
        }
        return $stock_qty;
    }


    private function overrideStock($id)
    {

        $check = StockCheck::find($id);
        if (!$check)
            return [
                'status' => false,
                'message' => 'Invalid Stock Check id'
            ];

        $type = $check->type;
        if ($type === 'location') {

            foreach ($check->products as $p) {
                $expectedStock = GrnProductDetail::where([
                    'location_id' => $check->location_id,
                    'product_id' => $p->product_id,
                ])->where('status', GrnProductDetail::STATUS['open']['id'])->get(['id', 'scanned_product_id'])->toArray();

                $actualStock = $check->productDetails()->where('product_id', $p->product_id)->get(['id', 'code'])->toArray();

                $expectedStock = array_column($expectedStock, 'scanned_product_id');
                $actualStock = array_column($actualStock, 'code');
                $result = array_diff($expectedStock, $actualStock);
                GrnProductDetail::whereIn('scanned_product_id', $result)
                    ->where(['location_id' => $check->location_id, 'product_id' => $p->product_id])
                    ->update(['status' => GrnProductDetail::STATUS['adjust']['id']]);
            }

        } else {
            foreach ($check->products as $p) {

                $expectedStockQuery = LabelProductDetail::where([
                    'product_id' => $p->product_id,
                ])->join('labels as l', 'l.id', '=', 'label_product_details.label_id')
                    ->where('l.agent_id', $check->agent_id)
                    ->where('l.status', '!=', Label::STATUS['closed']['id'])
                    ->where('label_product_details.status', LabelProductDetail::STATUS['issued']['id'])
                    ->get(['label_product_details.id', 'code', 'label_id'])->toArray();

                $actualStock = $check->productDetails()->where('product_id', $p->product_id)->get(['id', 'code'])->toArray();

                $expectedStock = array_column($expectedStockQuery, 'code');
                $actualStock = array_column($actualStock, 'code');
                $result = array_diff($expectedStock, $actualStock);

                $label_id = null;
                if ($expectedStockQuery)
                    $label_id = $expectedStockQuery[0]['label_id'] ?? null;

                LabelProductDetail::whereIn('code', $result)
                    ->where('label_id', $label_id)
                    ->where(['product_id' => $p->product_id])
                    ->update([
                        'status' => LabelProductDetail::STATUS['adjust']['id'],
                        'adjust' => now()
                    ]);
            }

        }

        return [
            'status' => true,
            'message' => 'Stock Check status updated successfully'
        ];

    }
    public function export(Request $request)
    {
        try {
            $role_id = auth()->user()->role_id;
            $user_location_id = auth()->user()->branch_office_id;
            $search = $request->input('search');
            $query = DB::table('stock_checks as s')
                ->join('branch_locations as l', 's.location_id', '=', 'l.id')
                ->select('s.id', 's.request_id', 'l.location as name', 's.type', 's.date', 's.status', 's.agent_id')
                ->where(function ($query) use ($search) {
                    $query->orWhere('s.request_id', 'like', "%$search%")
                        ->orWhere('l.location', 'like', "%$search%")
                        ->orWhere('s.type', 'like', "%$search%")
                        ->orWhere('s.date', 'like', "%$search%");
                });
            if ($role_id != UtilsHelper::SUPER_ADMIN_ROLE) {
                $query->where('s.location_id', $user_location_id);
            }
    
            $stock = $query->get();
            // Return the data for export
            return Excel::download(new StockExport($stock), 'Stock.xlsx');
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }
    
}

<?php

namespace App\Http\Controllers;

use App\Helpers\UtilsHelper;
use App\Models\GrnProductDetail;
use App\Models\Label;
use App\Models\LabelProduct;
use App\Models\LabelProductDetail;
use App\Models\Product;
use App\Models\StoRequestScannedProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \PDF;
use App\Helpers\MasterHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LabelExport;
use App\Exports\LabelRequestExport;
use App\Exports\LabelIssueExport;
use App\Exports\LabelReturnExport;

class LabelController extends Controller
{

    public function index(Request $request)
    {

        $user_id = auth()->user()->id;
        $role_id = auth()->user()->role_id;
        $user_location_id = auth()->user()->branch_office_id;

        $route_name = $request->route()->getName();

        $search = $request->input('search');
        $search_column = $request->input('search_column');
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $sort = $request->input('sort') ?? 'l.created_at|desc';
        if ($sort)
            $sort = explode('|', $sort);

        $status = Label::STATUS;


        $data = DB::table('labels as l')
            ->join('users as u', 'l.agent_id', '=', 'u.id')
            ->select('l.id', 'l.request_id', 'l.request_date', 'l.status', 'l.agent_id', 'u.name')
            ->where(function ($query) use ($search) {
                $query->orWhere('l.request_id', 'like', "%$search%")->orWhere('u.name', 'like', "%$search%");
            });

        if ($route_name == 'label-return.index') {
            $data = $data->where('l.status', '!=', Label::STATUS['requested']['id']);
        }

        if($role_id != UtilsHelper::SUPER_ADMIN_ROLE){
            $data = $data->where('l.location_id', $user_location_id);
        }

        $data = $data->orderBy($sort[0], $sort[1])
            ->paginate($per_page, ['*'], 'page', $page);
        $total = $data->total();

        if ($request->ajax()) {
            return [
                'table' => view('pages.label.components.table', compact('data', 'status'))->render(),
                'pagination' => view('components.pagination', compact('data'))->render(),
                'total' => $total,
            ];
        }

        $page = [
            'name' => 'Label Requests',
            'link' => 'label-request',
            'add' => 'Request Label',
        ];
        if ($route_name == 'label-issues.index') {
            $page = [
                'name' => 'Label Issue',
                'link' => 'label-issues',
                'add' => 'Issue Label',
            ];
        } else if ($route_name == 'label-return.index') {
            $page = [
                'name' => 'Label Return',
                'link' => 'label-return',
                'add' => 'Return Label',
            ];
        }

        return view('pages.label.index', compact('data', 'status', 'page'));
    }

    public function show(Request $request)
    {
        $id = $request->id;

        $result = $this->getLabelMaster();
        $agents = $result['agents'];
        $products = $result['products'];

        $label = null;
        $disabled = false;

        if (is_numeric($id)) {
            $label = Label::find($id);
            if (!$label)
                return redirect()->route('label-request.index')->with('error', 'Invalid label id');
            $request_id = $label->request_id;
            if ($label->status != Label::STATUS['requested']['id'])
                $disabled = true;
            if ($label->productDetails()->count() > 0)
                $disabled = true;
        } else {
            $request_id = UtilsHelper::getMaxRequestNo('as_labels');
        }

        return view('pages.label.action', compact('agents', 'products', 'label', 'request_id', 'disabled'));
    }

    public function showSummary(Request $request)
    {
        $id = $request->id;
        $label = Label::find($id);
        if (!$label)
            return redirect()->route('label-request.index')->with('error', 'Invalid label id');
        $status = LabelProduct::STATUS;
        return view('pages.label.summary', compact('label', 'status'));
    }

    public function pdfGenerator($id)
    {
        $data = Label::find($id);
        $fontPaths = [
            public_path('assets/font/NeueMontreal-Medium.otf'),
            public_path('assets/font/NeueMontreal-Regular.otf')
        ];
        $pdf = PDF::loadView('pdf.label', compact('data', 'fontPaths'));
        return response($pdf->output())->header('Content-Type', 'application/pdf');
        //return $pdf->download( 'label.pdf' );
    }


    public function save(Request $request)
    {
        try {
            $validator = $request->validate([
                'agent_id' => 'nullable|integer',
                'request_id' => 'nullable|string|max:50',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.qty' => 'required|integer|min:1',
            ]);

            $agent_id = $validator['agent_id'];
            $route_name = $request->route()->getName();

            $message = "Label created successfully";


            DB::beginTransaction();


            $agent = User::where(['id' => $agent_id, 'role_id' => UtilsHelper::FIELD_AGENT])
                ->first();
            if (!$agent)
                return $this->returnError('Invalid agent id');
            if ($agent->branch_office_id == null)
                return $this->returnError('Agent branch office is not set');

            if ($route_name == 'label-request.save') {

                $label = Label::where('agent_id', $agent_id)->where('status', '!=', Label::STATUS['closed']['id'])->first();
                if ($label)
                    return $this->returnError('invalid-status', 'Label is already requested');

                $no = UtilsHelper::getMaxRequestNo('as_labels');
                if ($no != $validator['request_id'])
                    return $this->returnError('invalid-request-no', [
                        'request_id' => $no,
                        'msg' => 'Your request id is already used by another user New Id: ' . $no
                    ]);

                $label = new Label();
                $label->request_id = $no;
                $label->request_date = now();
            } else {
                $label = Label::find($request->id);
                if ($label->status != Label::STATUS['requested']['id'])
                    return $this->returnError('invalid-status', 'Label is already processed');
                if ($label->location_id != $agent->branch_office_id)
                    return $this->returnError('invalid-branch-office', 'Agent branch office is not matched');

                $message = "Label updated successfully";
            }


            $label->agent_id = $agent_id;
            $label->created_by = auth()->user()->id;
            $label->location_id = $agent->branch_office_id;
            $total_qty = array_sum(array_column($validator['items'], 'qty'));
            $label->total_qty = $total_qty;
            $label->save();

            $items = $validator['items'];
            $label->products()->delete();
            foreach ($items as $item) {
                $label->products()->create($item);
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess($label->id, $message);

    }

    public function existRequest(Request $request)
    {
        $agent_id = $request->input('agent_id');
        $id = $request->input('id');

        $label = Label::where(['agent_id' => $agent_id])->where('status', '!=', Label::STATUS['closed']['id']);
        if ($id)
            $label = $label->where('id', '!=', $id);
        $label = $label->first();
        if ($label)
            return $this->returnError('exist', [
                'id' => $label->id,
                'msg' => 'You already have a request with id: ' . $label->request_id
            ]);
        return $this->returnSuccess('not-exist', 'You can request for label');

    }


//     issue label start

    public function showIssues(Request $request)
    {
        $id = $request->id;
        $result = $this->getLabelMaster();
        $agents = $result['agents'];
        $products = $result['products'];

        $label = null;
        $disabled = false;
        if (is_numeric($id)) {
            $label = Label::find($id);
            if (!$label)
                return redirect()->route('label-issues.index')->with('error', 'Invalid label id');
            $request_id = $label->request_id;
        } else {
            $request_id = UtilsHelper::getMaxRequestNo('as_labels');
        }

        return view('pages.label.issue.action', compact('agents', 'products', 'label', 'request_id', 'disabled'));
    }

    public function loadPreviousRequests(Request $request)
    {
        try {
            $agent_id = $request->route('agent_id');
            $label = Label::where('agent_id', $agent_id)
                ->where('status', '!=', Label::STATUS['closed']['id'])->first();
            if (!$label)
                return $this->returnError('No label found');
            $data = [
                'id' => $label->id,
                'request_id' => $label->request_id,
            ];
            foreach ($label->products as $product) {
                if ($product->status == LabelProduct::STATUS['closed']['id'])
                    continue;
                if ($product->status == LabelProduct::STATUS['issued']['id'] && $product->balance_qty == 0)
                    continue;
                // $qty = $product->balance_qty;
                $issued_qty = $product->balance_qty;
                if ($product->status == LabelProduct::STATUS['requested']['id']) {
                    //  $qty = $product->qty;
                    $issued_qty = 0;
                }


                $data['products'][] = [
                    'product_id' => $product->product_id,
                    'product_name' => $product->product->name,
                    'qty' => $product->qty,
                    'issued_qty' => $issued_qty
                ];
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        return $this->returnSuccess($data, 'Data loaded successfully');
    }


    public function verifyLabel(Request $request)
    {
        $agent_id = $request->input('agent_id');
        $product_id = $request->input('product_id');
        $code = $request->input('code');
        try {

            $user = User::find($agent_id);
            if (!$user)
                return $this->returnError('Invalid agent id');
            $location_id = $user->branch_office_id;
            if (!$location_id)
                return $this->returnError('Agent branch office is not set');

            $check = $this->checkLabelExist($location_id, $product_id, $code);
            if (!$check['status']) {
                return $this->returnError($check['message']);
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        return $this->returnSuccess([], 'Label is verified');
    }

    public function saveNewIssueRequest(Request $request)
    {
        try {
            $validator = $request->validate([
                'agent_id' => 'nullable|integer',
                'request_id' => 'nullable|string|max:50',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.qty' => 'required|integer|min:1',
            ]);

            DB::beginTransaction();

            $no = UtilsHelper::getMaxRequestNo('as_labels');
            if ($no != $validator['request_id'])
                return $this->returnError('invalid-request-no', [
                    'request_id' => $no,
                    'msg' => 'Your request id is already used by another user New Id: ' . $no
                ]);

            $label = new Label();
            $label->request_id = $no;
            $label->request_date = now();

            $agent_id = $validator['agent_id'];
            $agent = User::where(['id' => $agent_id, 'role_id' => UtilsHelper::FIELD_AGENT])
                ->first();
            if (!$agent)
                return $this->returnError('Invalid agent id');
            if ($agent->branch_office_id == null)
                return $this->returnError('Agent branch office is not set');


            $label->agent_id = $agent_id;
            $label->created_by = auth()->user()->id;
            $label->location_id = $agent->branch_office_id;
            $total_qty = array_sum(array_column($validator['items'], 'qty'));
            $label->total_qty = $total_qty;
            $label->save();

            $items = $validator['items'];

            foreach ($items as $item) {
                $label->products()->create($item);
            }

            $prevLabel = Label::where('agent_id', $agent_id)
                ->where('status', '!=', Label::STATUS['closed']['id'])
                ->where('id', '!=', $label->id)
                ->first();
            if ($prevLabel) {

                if($agent->branch_office_id !== $prevLabel->location_id)
                    return $this->returnError('Agent branch office is not matched');


                if ($prevLabel->status == Label::STATUS['requested']['id']) {
                    $prevLabel->status = Label::STATUS['closed']['id'];
                    $prevLabel->save();
                }

                //else if ($prevLabel->status == Label::STATUS['issued']['id']) {

                $issued_label = LabelProductDetail::where('label_id', $prevLabel->id)
                    ->where('status', LabelProductDetail::STATUS['issued']['id'])->get();

                if ($issued_label->count() > 0) {
                    $product_moved = [];
                    foreach ($issued_label as $item) {
                        $label->productDetails()->create([
                            'product_id' => $item->product_id,
                            'code' => $item->code,
                            'issued' => now(),
                            'status' => LabelProductDetail::STATUS['issued']['id']
                        ]);
                        $item->delete();
                        if (!isset($product_moved[$item->product_id]))
                            $product_moved[$item->product_id] = 0;
                        $product_moved[$item->product_id] += 1;
                    }

                    foreach ($product_moved as $key => $value) {
                        $product = LabelProduct::where('label_id', $prevLabel->id)->where('product_id', $key)->first();
                        if ($product) {
                            $product->moved_qty += $value;
                            $product->save();
                        }
                    }

                }
            }
            //  }


            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess($label->id, 'Label created successfully');
    }

    public function saveIssues(Request $request)
    {
        try {

            $validator = $request->validate([
                'details' => 'required|array|min:1',
                'details.*.code' => 'required|string|distinct',
                'details.*.product_id' => 'required|integer',
            ]);

            DB::beginTransaction();

            $label = Label::find($request->id);
            if ($label->status != Label::STATUS['requested']['id'])
                return $this->returnError('Label is already ' . Label::STATUS[$label->status]['label']);

            $label->status = Label::STATUS['issued']['id'];
            $label->issued_by = auth()->user()->id;
            $label->issued_at = now();
            $label->save();
            $details = $validator['details'];


            foreach ($label->products as $product) {
                $detail = array_filter($details, function ($item) use ($product) {
                    return $item['product_id'] == $product->product_id;
                });
                if (count($detail) != $product->qty) {
                    return $this->returnError('Please issue ' . $product->qty . ' qty for product id: ' . $product->product_id);
                }
            }
            //            $detail_count = $label->productDetails()->count();
            //            if ($detail_count > 0) {
            //                return $this->returnError('Label is already issued');
            //            }

            foreach ($details as $detail) {

                $labelPrdDet = LabelProductDetail::where([
                    'label_id' => $label->id,
                    'product_id' => $detail['product_id'],
                    'code' => $detail['code']])->first();
                if ($labelPrdDet)
                    continue;

                $detail['issued'] = now();
                $detail['status'] = LabelProductDetail::STATUS['issued']['id'];
                $check = $this->checkLabelExist($label->location_id, $detail['product_id'], $detail['code']);
                if (!$check['status']) {
                    return $this->returnError($check['message']);
                }

                $grnProduct = GrnProductDetail::find($check['id']);
                $grnProduct->status = GrnProductDetail::STATUS['issued']['id'];
                $grnProduct->save();

                $label->productDetails()->create($detail);
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess($label->id, 'Label is issued successfully');
    }

//     label return start

    public function showReturn(Request $request)
    {
        $id = $request->id;
        $result = $this->getLabelMaster();
        $agents = $result['agents'];
        $products = $result['products'];
        $request_nos = [];

        $label = null;
        $request_id = null;
        if (is_numeric($id)) {
            $label = Label::find($id);
            if (!$label)
                return redirect()->route('label-return.index')->with('error', 'Invalid label id');
            $request_id = $label->request_id;
        } else {
            $request_nos = Label::where('status', Label::STATUS['issued']['id'])->get(['id', 'request_id'])->toArray();
        }

        $is_returned = true;

        return view('pages.label.return.action', compact('agents', 'products', 'label', 'request_id', 'is_returned', 'request_nos'));
    }

    public function requestDetails(Request $request)
    {
        $agent_id = $request->input('agent_id');
        $id = $request->input('id');

        $label = Label::where('status', Label::STATUS['issued']['id']);
        if ($agent_id)
            $label->where('agent_id', $agent_id);
        if ($id)
            $label->where('id', $id);

        $label = $label->first();

        if (!$label)
            return $this->returnError('No label found');

        return [
            'table' => view('pages.label.components.return-table', compact('label'))->render(),
            'id' => $label->id,
            'request_id' => $label->request_id,
            'agent_id' => $label->agent_id,
            'products' => $label->products,
            'return_products' => $label->returnedProductDetails,

        ];

    }


    public function saveReturn(Request $request)
    {
        try {

            $validator = $request->validate([
                'details' => 'required|array|min:1',
                'details.*.code' => 'required|string|distinct',
                'details.*.product_id' => 'required|integer',
            ]);

            DB::beginTransaction();

            $label = Label::find($request->id);
            if ($label->status != Label::STATUS['issued']['id'])
                return $this->returnError('Label is already ' . Label::STATUS[$label->status]['label']);

            $label->return_by = auth()->user()->id;
            $label->return_at = now();
            $label->save();
            $details = $validator['details'];

            $product_status = LabelProductDetail::STATUS;

            foreach ($details as $detail) {
                $prd_detail = LabelProductDetail::where(['label_id' => $label->id, 'code' => $detail['code'], 'product_id' => $detail['product_id']])->first();
                if (!$prd_detail)
                    return $this->returnError('Invalid code for product id: ' . $detail['product_id']);
                if ($prd_detail->status == $product_status['returned']['id'])
                    continue;
                if ($prd_detail->status != $product_status['issued']['id'])
                    return $this->returnError('Code is already ' . $product_status[$prd_detail->status]['label']);


                $prd_detail->returned = now();
                $prd_detail->status = $product_status['returned']['id'];
                $prd_detail->save();

                $grnProduct = GrnProductDetail::where([
                        'location_id' => $label->location_id,
                        'product_id' => $detail['product_id'],
                        'scanned_product_id' => $detail['code']]
                )->first();
                $grnProduct->status = GrnProductDetail::STATUS['open']['id'];
                $grnProduct->save();


            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess($label->id, 'Label is issued successfully');
    }

    public function verifyReturnLabel(Request $request)
    {
        $code = $request->input('code');
        $label_id = $request->input('label_id');
        $product_id = $request->input('product_id');
        try {
            $check = $this->checkReturnLabelExist($label_id, $product_id, $code);
            if (!$check['status']) {
                return $this->returnError($check['message']);
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        return $this->returnSuccess([], 'Label is verified');
    }


    private function checkLabelExist($location_id, $product_id, $code)
    {

        $status = false;
        $message = 'Invalid code';
        $id = null;

        $codeExist = GrnProductDetail::where(['location_id' => $location_id, 'product_id' => $product_id, 'scanned_product_id' => $code])
            ->orderByRaw('FIELD(status, "open") desc')->first();

        if ($codeExist) {
            if ($codeExist->status == GrnProductDetail::STATUS['open']['id']) {
                $id = $codeExist->id;
                $status = true;
                $message = 'Code is verified';
            } else {
                $message = 'Code is already ' . GrnProductDetail::STATUS[$codeExist->status]['label'];
            }
        }

        return [
            'id' => $id,
            'code' => $code,
            'status' => $status,
            'message' => $message
        ];

    }

    private function checkReturnLabelExist($label_id, $product_id, $code)
    {

        $status = true;
        $message = 'Code is verified';
        $code = LabelProductDetail::where(['label_id' => $label_id, 'product_id' => $product_id, 'code' => $code])->first();
        if (!$code) {
            $status = false;
            $message = 'Invalid code';
        } else {
            if ($code->status != LabelProduct::STATUS['issued']['id']) {
                $status = false;
                $message = 'Code is already ' . LabelProduct::STATUS[$code->status]['label'];
            }
        }
        return [
            'code' => $code,
            'status' => $status,
            'message' => $message
        ];
    }


    private function getLabelMaster()
    {
        $agent_role_id = UtilsHelper::FIELD_AGENT;

        $field_agent_where = ['status' => 1, 'role_id' => $agent_role_id];

        if(auth()->user()->role_id != UtilsHelper::SUPER_ADMIN_ROLE) {
            $field_agent_where['branch_office_id'] = auth()->user()->branch_office_id;
        }

        $agents = User::where($field_agent_where)
            ->orderBy('name')->get(['id', 'name'])
            ->toArray();
        $products = Product::where('status', 1)->orderBy('name')->get(['id', 'name'])->toArray();
        return compact('agents', 'products');
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

                if($role_id != UtilsHelper::SUPER_ADMIN_ROLE){
                    $query = $query->where('l.location_id', $user_location_id);
                }

            $label = $query->get();

            return Excel::download(new LabelExport($label), 'label.xlsx');
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }
    public function exportview(Request $request)
    {
       
        $id = $request->query('id');
        $data = Label::where('id', $id)->first();
        if (!$data) {
            abort(404, 'Data not found');
        }
        $export = new LabelRequestExport($data);
        return Excel::download($export, 'label-requestview.xlsx');
    }

    public function exportissue(Request $request)
    {
       
        $id = $request->query('id');
        $data = Label::where('id', $id)->first();
        if (!$data) {
            abort(404, 'Data not found');
        }
        $export = new LabelIssueExport($data);
        return Excel::download($export, 'label-requestissue.xlsx');
    }

    public function exportreturn(Request $request)
    {
        $id = $request->query('id');
        $data = Label::where('id', $id)->first();
        if (!$data) {
            abort(404, 'Data not found');
        }
        $export = new LabelReturnExport($data);
        return Excel::download($export, 'label-requestreturn.xlsx');
    }
    


}

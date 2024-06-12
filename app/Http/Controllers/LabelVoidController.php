<?php

namespace App\Http\Controllers;

use App\Models\BranchLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Label;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\LabelVoid;
use App\Models\User;

class LabelVoidController extends Controller
{

    public function index(Request $request)
    {
        $search_column = $request->input('search_column');
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $sort = $request->input('sort') ?? 'id|desc';
        $search = $request->input('search');
        $location_id = $request->input('location_id');
        $product_id = $request->input('agent_id');
        $status_id = $request->input('status');

        $query = LabelVoid::with('location', 'agent');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('agent', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('location', function ($query) use ($search) {
                        $query->where('location', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($location_id)
            $query->whereIn('location_id', $location_id);

        if ($product_id)
            $query->whereIn('agent_id', $product_id);

        if ($sort)
            $sort = explode('|', $sort);

        $data = $query->orderBy($sort[0], $sort[1])->paginate($per_page, ['*'], 'page', $page);
        $products = User::all();
        $locations = BranchLocation::all();
        $total = $data->total();
        if ($request->ajax()) {
            return [
                'table' => view('pages.label-void.table', compact('data', 'total'))->render(),
                'pagination' => view('components.pagination', compact('data'))->render(),
            ];
        }


        return view('pages.label-void.index', compact('data', 'search', 'total',  'product_id', 'location_id', 'locations', 'products'));
    }
}

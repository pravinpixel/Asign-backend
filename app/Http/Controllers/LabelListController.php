<?php

namespace App\Http\Controllers;

use App\Models\BranchLocation;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\GrnProductDetail;

class LabelListController extends Controller
{
    public function index(Request $request)
    {
        $search_column = $request->input('search_column');
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $sort = $request->input('sort') ?? 'id|desc';
        $search = $request->input('search');
        $location_id = $request->input('location_id');
        $product_id = $request->input('product_id');
        $status_id = $request->input('status');
    
        $query = GrnProductDetail::with('location', 'product');
    
        if ($search)
            $query->where('scanned_product_id', 'like', '%' . $search . '%');
    
        if ($location_id)
            $query->whereIn('location_id', $location_id);
    
        if ($product_id)
            $query->whereIn('product_id', $product_id);
    
        if ($status_id)
            $query->whereIn('status', $status_id);
    
        if ($sort)
            $sort = explode('|', $sort);
    
        $data = $query->orderBy($sort[0], $sort[1])->paginate($per_page, ['*'], 'page', $page);
 
        
        $products = Product::all();
        $locations = BranchLocation::all();
        $status = GrnProductDetail::STATUS;
        $total = $data->total();
        if ($request->ajax()) {
            return [
                'table' => view('pages.label_list.table', compact('data', 'status','total'))->render(),
                'pagination' => view('components.pagination', compact('data'))->render(),
            ];
        }
        return view('pages.label_list.index', compact('data', 'search','total', 'status', 'status_id', 'product_id', 'location_id', 'locations', 'products'));
    }
    


}

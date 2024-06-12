<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProtectApprovedController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $search_column = $request->input('search_column');
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $sort = $request->input('sort') ?? 'created_at|desc';

        $customer_type = $request->input('customer_type');
        $aa_no = $request->input('aa_no');
        $full_name = $request->input('full_name');

        if ($sort)
            $sort = explode('|', $sort);

        $data = DB::table('artwork_protect_requests as apr')
            ->where('apr.status', '=', 'approved')
            ->join('artworks as a', 'a.id', '=', 'apr.artwork_id')
            ->leftJoin('customers as c', 'c.id', '=', 'a.artist_id')
            ->join('customers as cr', 'cr.id', '=', 'apr.customer_id');

        if ($aa_no)
            $data->whereIn('c.aa_no', $aa_no);
        if ($full_name)
            $data->whereIn('c.full_name', $full_name);
        if ($customer_type)
            $data->whereIn('cr.account_type', $customer_type);

        $data = $data
            ->select('apr.approved_at as aging', 'apr.artwork_id', 'a.artist_id', 'a.unknown_artist', 'c.full_name', 'cr.aa_no', 'a.asign_no', 'cr.account_type', 'apr.id', 'apr.created_at')
            ->addSelect(DB::raw('IFNULL((SELECT value FROM as_artwork_media WHERE artwork_id = as_apr.artwork_id and tag="featured"), "") as image'))
            ->addSelect(DB::raw('IFNULL((SELECT count(id) FROM as_customer_wishlists WHERE artwork_id = as_apr.artwork_id), 0) as likes'))
            ->addSelect(DB::raw('IFNULL((SELECT count(id) FROM as_customer_artwork_views WHERE artwork_id = as_apr.artwork_id), 0) as views'))
            ->where(function ($query) use ($search, $search_column) {
//                if ($search_column == 'asign_no' || !$search_column)
//                    $query->orWhere('a.asign_no', 'like', "%$search%");
                if ($search_column == 'aa_no' || !$search_column)
                    $query->orWhere('cr.aa_no', 'like', "%$search%");
                if ($search_column == 'full_name' || !$search_column)
                    $query->orWhere('c.full_name', 'like', "%$search%")->orWhere('a.unknown_artist', 'like', "%$search%");
            })
            ->orderBy($sort[0], $sort[1])
            ->paginate($per_page, ['*'], 'page', $page);
        $customers = Customer::all();
        $total = $data->total();
        if ($request->ajax()) {
            return [
                'table' => view('pages.protect_approved.table', compact('customers', 'data'))->render(),
                'pagination' => view('components.pagination', compact('data'))->render(),
                'total' => $total,
            ];
        }
        return view('pages.protect_approved.index', compact('total', 'customers', 'data'));

    }
}

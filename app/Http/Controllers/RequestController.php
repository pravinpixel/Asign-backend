<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArtworkImageRequest;
use App\Models\ArtworkPriceRequest;
use App\Models\ArtworkPrivateViewRequest;
use App\Models\ArtworkOfferRequest;
use App\Models\AdditionalImage;

class RequestController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;

    public function imageRequestList(Request $request)
{
    try {
        $search = $request->input("search", "");
        $search_key = $request->input("search_key", "all");
        $sort = $request->input('sort') ?? 'created_at|desc';
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);

        if ($sort)
            $sort = explode('|', $sort);

        $this->pageNumber = $page;
        $this->perPage = $per_page;

        $query = ArtworkImageRequest::query();

        if (!empty($search) && !empty($search_key)) {
            if ($search_key == "all") {
                $query->whereHas('artwork', function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%");
                })->orWhereHas('customer', function ($query) use ($search) {
                    $query->where('display_name', 'like', "%{$search}%");
                });
            } else {
                $query->where($search_key, "like", "%" . $search . "%");
            }
        }

        $query->with('artwork');

        $data = $query->orderBy($sort[0], $sort[1])
                     ->paginate($per_page, ['*'], 'page', $page);

        foreach ($data as $item) {
            $imageIds = explode(',', $item->image_ids);
            $item->images = AdditionalImage::whereIn('id', $imageIds)->get();
        }

        $total = $data->total();

        if ($request->ajax()) {
            return [
                'table' => view('pages.artwork_request.image_request.tab', compact('data'))->render(),
                'pagination' => view('components.pagination', compact('data'))->render(),
                'total' => $total,
            ];
        }

        return view('pages.artwork_request.image_request.index', compact('data'));
    } catch (\Exception $e) {
        return $this->returnError($e->getMessage());
    }
}


    public function priceRequestList(Request $request)
    {
        try {
            $search = $request->input("search", "");
            $search_key = $request->input("search_key", "all");
            $sort = $request->input('sort') ?? 'created_at|desc';
            $page = $request->input('page', 1);
            $per_page = $request->input('per_page', 10);
            if ($sort)
            $sort = explode('|', $sort);
        
    
            $this->pageNumber = $page;
            $this->perPage = $per_page;
    
            $query = ArtworkPriceRequest::query();
    
            if (!empty($search) && !empty($search_key)) {
                if ($search_key == "all") {
                    $query->whereHas('artwork', function ($query) use ($search) {
                        $query->where('title', 'like', "%{$search}%");
                    })->orWhereHas('customer', function ($query) use ($search) {
                        $query->where('display_name', 'like', "%{$search}%");
                    });
                } else {
                    $query->where($search_key, "like", "%" . $search . "%");
                }
            }
            
    
            $query->with('artwork');
    
            $data = $query->orderBy($sort[0], $sort[1])
            ->paginate($per_page, ['*'], 'page', $page);
    
            $total = $data->total();
    
            if ($request->ajax()) {
                return [
                    'table' => view('pages.artwork_request.price_request.tab', compact('data'))->render(),
                    'pagination' => view('components.pagination', compact('data'))->render(),
                    'total' => $total,
                ];
            }
    
            return view('pages.artwork_request.price_request.index', compact('data'));
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    } 

    public function viewRequestList(Request $request)
    {
        try {
            $search = $request->input("search", "");
            $search_key = $request->input("search_key", "all");
            $sort = $request->input('sort') ?? 'created_at|desc';
            $page = $request->input('page', 1);
            $per_page = $request->input('per_page', 10);
            if ($sort)
            $sort = explode('|', $sort);
    
            $this->pageNumber = $page;
            $this->perPage = $per_page;
    
            $query = ArtworkPrivateViewRequest::query();
    
            if (!empty($search) && !empty($search_key)) {
                if ($search_key == "all") {
                    $query->whereHas('artwork', function ($query) use ($search) {
                        $query->where('title', 'like', "%{$search}%");
                    })->orWhereHas('customer', function ($query) use ($search) {
                        $query->where('display_name', 'like', "%{$search}%");
                    });
                } else {
                    $query->where($search_key, "like", "%" . $search . "%");
                }
            }
             $query->with('artwork');
            
            $data = $query->orderBy($sort[0], $sort[1])
            ->paginate($per_page, ['*'], 'page', $page);

            $total = $data->total();
    
            if ($request->ajax()) {
                return [
                    'table' => view('pages.artwork_request.view_request.tab', compact('data'))->render(),
                    'pagination' => view('components.pagination', compact('data'))->render(),
                    'total' => $total,
                ];
            }
    
            return view('pages.artwork_request.view_request.index', compact('data'));
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    } 

    public function offerRequestList(Request $request)
    {
        try {
            $search = $request->input("search", "");
            $search_key = $request->input("search_key", "all");
            $sort = $request->input('sort') ?? 'created_at|desc';
            $page = $request->input('page', 1);
            $per_page = $request->input('per_page', 10);
            if ($sort)
            $sort = explode('|', $sort);
    
            $this->pageNumber = $page;
            $this->perPage = $per_page;
    
            $query = ArtworkOfferRequest::query();
    
            if (!empty($search) && !empty($search_key)) {
                if ($search_key == "all") {
                    $query->whereHas('artwork', function ($query) use ($search) {
                        $query->where('title', 'like', "%{$search}%");
                    })->orWhereHas('customer', function ($query) use ($search) {
                        $query->where('display_name', 'like', "%{$search}%");
                    });
                } else {
                    $query->where($search_key, "like", "%" . $search . "%");
                }
            }
            
    
            $query->with('artwork');
    
            $data = $query->orderBy($sort[0], $sort[1])
            ->paginate($per_page, ['*'], 'page', $page);
    
            $total = $data->total();
    
            if ($request->ajax()) {
                return [
                    'table' => view('pages.artwork_request.offer_request.tab', compact('data'))->render(),
                    'pagination' => view('components.pagination', compact('data'))->render(),
                    'total' => $total,
                ];
            }
    
            return view('pages.artwork_request.offer_request.index', compact('data'));
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    } 
}

<?php

namespace App\Http\Controllers\Masters;
use Illuminate\Support\Facades\Validator;
use App\Models\MediumType;
use App\Models\Medium;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediumTypeController extends Controller
{
    public function index(Request $request, $slug)
    {
       
        $names=ucfirst($slug);
      
        // Fetch the Medium using the provided slug
        $medium = Medium::where('slug', $slug)->firstOrFail();
    
        $search = $request->input('search');
        $status = $request->input('status');
        $perPage = 10; 
    
        $query = MediumType::query();
    
        // Filter the MediumType records by the found Medium's id
        $query->where('medium_id', $medium->id);
    
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
    
        if ($status === '1' || $status === '0') {
            $query->where('status', $status);
        }
    
        $types = $query->orderBy('id','desc')->paginate($perPage);
  
      
      $currentPage = $types->currentPage();
      $serialNumberStart = ($currentPage - 1) * $perPage + 1;

        $mediums=Medium::all();
      
        return view('pages.masters.medium.type.index', [
            'types' => $types,
            'names'=>$names,
            'selectedStatus' => $status,
            'search' => $search,
            'mediums'=> $mediums,
            'serialNumberStart' => $serialNumberStart,
        
        ]);
    }
    
     
  public function save(Request $request)
  {
    $credentials = $request->validate([
      'name' => 'required|unique:medium_types',
        'status'=>'required|boolean'
      ]);
   
     try{ 
        $slug = $request->input('slug');
        $medium = Medium::where('slug', $slug)->firstOrFail();
        $id=$medium->id;
       
      $type = new MediumType();
      $type->medium_id = $id;
      $type->name = $request->input('name');
      $type->status = $request->input('status');
    $type->save();
    return response()->json(['message' => 'medium type created successfully', 'data' => $type]);
  }catch (\Exception $e) {
  return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
  }
  }
  public function get(Request $request,$id)
  {
    $type=MediumType::find($id);

    return response()->json($type);

  }
  public function update(Request $request,$id)
  {
    $credentials = $request->validate([
      'name' => 'required|unique:medium_types,name,' . $id,
      'status'=>'required|boolean'
    ]);
    try{ 
      
     
      $type =MediumType::find($id);
    
      $type->name = $request->input('name');
      $type->status = $request->input('status');
    $type->update();
    return response()->json(['message' => ' medium type updated successfully', 'data' => $type]);
  }catch (\Exception $e) {
  return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
  }
  }
  public function delete($id)
  {
    try{
      $type=MediumType::find($id)->delete();    
      return response()->json(['message' => 'meduim type deleted successfully']);
     }catch (\Exception $e) {
     return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
     }

  }

}

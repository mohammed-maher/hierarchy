<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Hierarchy as HierarchyHelper;

class HierarchyController extends Controller
{
    /**
     * Capture json employee=>manager data, validate it and return hierarchy json or an error if any
     */
    public function handle(Request $request){
        try{
            if($request->hasFile('fileData')){
                $data=json_decode(file_get_contents($request->file('fileData')));
            }else{
                $data=$request->json()->all();
            }
            
            $hierarchy = HierarchyHelper::sortHierarchy($data);

            return response()->json($hierarchy);

        }catch(\Exception $e){
            return response()->json(['error'=>'An error occurred, please make sure to submit valid data'.$e->getMessage()],422);
        }   
    }
}

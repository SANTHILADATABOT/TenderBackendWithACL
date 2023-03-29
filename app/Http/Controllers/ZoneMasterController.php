<?php

namespace App\Http\Controllers;

use App\Models\ZoneMaster;
use App\Models\StateMaster;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ZoneMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zonemaster=ZoneMaster::select('*')->get();
        return response()->json([
            'status' => 200,
            'zonemaster' => $zonemaster]);
    }

    
    public function create()
    {
        
    }

    
    public function store(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenId)->first();
        if($user['userid'])
        {
        $zone = ZoneMaster::where('zone_name', '=', $request->zonename)->exists();
        if ($zone) {
            return response()->json([
                'status' => 400,
                'errors' => 'Zone Name Already Exists'
            ]);
        }

        $validator = Validator::make($request->all(), ['zonename' => 'required|string', 'status' => 'required|string']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        
        $zone = new ZoneMaster;
        $zone->zone_name = $request->zonename;
        $zone->active_status=$request->status;
        $zone->save();

        
        foreach($request->statelist as $row)
        {
            $zonehasstate = StateMaster::find($row['value']);
            $zonehasstate->zone_id= $zone->id;
            $zonehasstate->save();
        }
        

        if ($zone && $zonehasstate) {
            return response()->json([
                'status' => 200,
                'message' => 'Tender Type Added Succssfully!'
            ]);
        }
        }

    }

    
    public function show($id)
    {
        try{
        $zonemaster = DB::table('zone_masters as z')
        // ->join('zone_has_states as sub','z.id','sub.zone_id')
        ->join('state_masters as s','z.id','s.zone_id')
        ->select('z.id as zone_id','z.zone_name','z.active_status','s.id as state_id','s.state_name')
        ->where('z.id',$id)
        ->get();

        if($zonemaster){
            $zone['zone_id'] = $zonemaster[0]->zone_id;
            $zone['zone_name'] = $zonemaster[0]->zone_name;
            $zone['active_status'] = $zonemaster[0]->active_status;
            $i=0;
            foreach($zonemaster as $z)
            {
                $zone['statelist'][$i] = ['value' => $z->state_id, 'label' => $z->state_name];
                $i++;
            }

            return response()->json([
                'status' => 200,
                'zonename' => $zone
            ]);
        }
    }
    catch(\Exception $ex){
        return response()->json([
            'status' => 400,
            'message' => 'Unable to handle',
            'error' => $ex
        ]);
    }
    }

    
    public function edit(ZoneMaster $zoneMaster)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
       
        $user = Token::where("tokenid", $request->tokenId)->first();
        if($user['userid'])
        {
        $zone = ZoneMaster::find($id);
        if (!$zone) {
            return response()->json([
                'status' => 400,
                'errors' => 'Somthing Wenr Wrong'
            ]);
        }

        $validator = Validator::make($request->all(), ['zonename' => 'required|string', 'status' => 'required|string']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        
        
        $zone->zone_name = $request->zonename;
        $zone->active_status=$request->status;
        $zone->save();
        // $zonehasstate = StateMaster::where('zone_id',$id)->get();
        // return $zonehasstate;
        foreach($request->statelist as $row)  //input
        {      echo "Foreach 1 ***";
            $state = StateMaster::get();
            foreach($state as $zs) //state list which has zone id
            {  
                $state_update =StateMaster::where('id',$zs->id)->firstOrFail();
                // echo $zs;
                // echo "      ";
                // echo $zs->id.'=='.$row['value'].'  &&  '.$zs->zone_id.'=='. $id;
              echo $state_update;
                // if(($zs->id == $row['value']) && ($zs->zone_id == $id))
                // {
                // // echo " -- In IF --- ";
                // // break;
                // } 
                 if($zs->id != $row['value'] && $zs->zone_id == $id){
                    $state_update->zone_id= null;
                    $state_update->save();
                    // $update =  DB::table('state_masters')
                    // ->where('id',$zs->id)  // find your user by their email
                    // ->limit(1)  // optional - to ensure only one record is updated.
                    // ->update(array('zone_id' => null)); 

                    echo " --- In If  ---    ";               
                    break;
                    // $zone_delete = StateMaster::destroy($zs->id);
                }
                else if($zs->id == $row['value'] && $zs->zone_id != $id){
                    echo "--  In Else IF 2  ---  ";
                    $state_update->zone_id= $id;
                    $state_update->save();


                    // $state_update =StateMaster::where('id',$zs->id)->firstOrFail();
                    // $state_update->zone_id = $id; 
                    // $state_update->save();

        //            $update =  DB::table('state_masters')
        // ->where('id',$zs->id)  // find your user by their email
        // ->limit(1)  // optional - to ensure only one record is updated.
        // ->update(array('zone_id' => $id));  // update the record in the DB. 

       
                    break;
                }
                // if($state_update){
                //     echo "              ------     $state_update    ";
                //     $state_update->save();
                // }
                // echo " **** ";
            }
        }
        }

        if ($zone ) {
            return response()->json([
                'status' => 200,
                'message' => 'Zone Master Update Succssfully!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ZoneMaster  $zoneMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(ZoneMaster $zoneMaster)
    {
        //
    }
}

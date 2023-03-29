<?php

namespace App\Http\Controllers;

use App\Models\BusinessForecast;
use App\Models\CallType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;

class BusinessForecastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $business_forecast = DB::table('business_forecasts')
        // ->join('call_types_mst','call_types_mst.id','=','business_forecasts.call_type_id')
        // ->where('call_types_mst.activeStatus','=','Active')
        // ->select('call_types_mst.name','business_forecasts.*')
        // ->orderBy('business_forecasts.name', 'asc')
        //  ->get();
 
         $business_forecast = BusinessForecast::select('business_forecasts.*','call_types_mst.name as calltype_name')
         ->leftJoin('call_types_mst','call_types_mst.id','business_forecasts.call_type_id')
         ->get();

//         $query = str_replace(array('?'), array('\'%s\''), $business_forecast->toSql());
// $query = vsprintf($query, $business_forecast->getBindings());
// return $query;

        if ($business_forecast)
            return response()->json([
                'status' => 200,
                'bizzforecast' => $business_forecast
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return "Create Function";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Token::where('tokenid', $request->tokenId)->first();
        
        if($user['userid'])
        {
        $business_forecast = BusinessForecast::where('name', '=', $request->name)->exists();
        if ($business_forecast) {
            return response()->json([
                'status' => 400,
                'message' => 'Business Forecast Name Already Exists!'
            ]);
        }

        $request->request->add(['created_by' => $user['userid']]);

        // $validator = Validator::make($request->all(), ['call_type_name' => 'required|string', 'status' => 'required']);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => $validator->messages(),
        //     ]);
        // }

        $request->request->remove('tokenId');
       
        $business_forecast_add = BusinessForecast::firstOrCreate($request->all());
        if ($business_forecast_add) {
            return response()->json([
                'status' => 200,
                'message' => 'Business Forecast Created Succssfully!'
            ]);
        }
    }
    else{
        return response()->json([
            'status' => 400,
            'message' => 'Provided Credentials are Incorrect!'
        ]);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BusinessForecast  $businessForecast
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  $business_forecast1 = BusinessForecast::find($id);
       //  $business_forecast = CallType::find($id);
         //$business_forecast = DB::table('call_types_mst')->select('name')->get();


        // // return "BB".$business_forecast;
        //  if ($business_forecast)
        //      return response()->json([
        //          'status' => 200,
        //          'bizzforecast' => $business_forecast
        //      ]);
        //  else {
        //      return response()->json([
        //          'status' => 404,
        //          'message' => 'The provided credentials are Invalid'
        //      ]);
        //  }

        $business_forecast = DB::table('business_forecasts as b')
        ->join("call_types_mst as c", "c.id", "b.call_type_id")
        ->where("b.id",$id)
        ->select(
            'b.name',
            'b.activeStatus',
            "c.id"
        )
        ->get();

// return $business_forecast;
//         foreach( $business_forecast as $row){
//             echo "row --  $row   ---";
//             // $calltype[] = ["value" => $row['id'], "label" =>  $row['callname']] ;
//         }
        
  
        if ($business_forecast)
             return response()->json([
                 'status' => 200,
                 'bizzforecast' => $business_forecast,
                //  'bizzforecast1' => $business_forecast1,
             ]);
         else {
             return response()->json([
                 'status' => 404,
                 'message' => 'The provided credentials are Invalid'
             ]);
         }

            // $callTypeList= [];
            //         foreach($business_forecast as $calltypes){
            //             $callTypeList[] = ["value" => $calltypes['id'], "label" =>  $calltypes['name']];
            //         }
            //         return response()->json([
            //             'bizzforecast' =>  $callTypeList,
            //         ]);

        
        // $business_forecast_name = BusinessForecast::find($id);
        // $business_forecast = BusinessForecast::where("id",$id)->get();
    
        // $callTypeList= [];
        // foreach($business_forecast as $calltypes){
        //     $callTypeList[] = ["value" => $calltypes['id'], "label" =>  $calltypes['name']];
        // }
        // return response()->json([
        //     'bizzforecast1' => $business_forecast_name,
        //     'bizzforecast' =>  $callTypeList,
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BusinessForecast  $businessForecast
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return "Edit Function";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BusinessForecast  $businessForecast
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Token::where('tokenid', $request->tokenId)->first();
        if($user['userid'])
        {
        $business_forecast = BusinessForecast::where('call_type_id', '=', $request->call_type_id)
        ->where('name', '=', $request->name)
        ->where('activeStatus', '=', $request->activeStatus)
        ->where('id', '!=', $id)->exists();
    if ($business_forecast) {
        return response()->json([
            'status' => 400,
            'message' => 'Business Forecast Name Already Exists!'
        ]);
        }
        }
    $request->request->add(['updated_by' => $user['userid']]);
    // $validator = Validator::make($request->all(), ['unit_name' => 'required|string', 'unit_status' => 'required']);
    // if ($validator->fails()) {
    //     return response()->json([
    //         'status' => 400,
    //         'message' => $validator->message(),
    //     ]);
    // } 

    $request->request->remove('tokenId');
    
    $business_forecast_update = BusinessForecast::findOrFail($id)->update($request->all());

        if ($business_forecast_update)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!",
            ]);
        else{
            return response()->json([
                'status' => 400,
                'message' => "Sorry, Failed to Update, Try again later"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BusinessForecast  $businessForecast
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $business_forecast_del = BusinessForecast::destroy($id);
        if ($business_forecast_del)
            return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!"
            ]);

        else {
            return response()->json([
                'status' => 400,
                'message' => 'The Provided Credentials are Incorrect.'
            ]);
        }
    }
}

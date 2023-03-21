<?php

namespace App\Http\Controllers;

use App\Models\BusinessForecast;
use App\Models\CallType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessForecastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $business_forecast = DB::table('business_forecasts')
        ->join('call_types_mst','call_types_mst.id','=','business_forecasts.call_type_id')
        ->where('call_types_mst.activeStatus','=','Active')
        ->select('call_types_mst.*','business_forecasts.*')
        ->orderBy('call_types_mst.name', 'asc')
        ->orderBy('business_forecasts.business_forecasts_name', 'asc')
        ->get();

        if ($business_forecast)
            return response()->json([
                'status' => 200,
                'business_forecast' => $business_forecast
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
        $business_forecast = BusinessForecast::where('business_forecast_name', '=', $request->business_forecast_name)->exists();
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
        $business_forecast = BusinessForecast::find($id);
        if ($business_forecast)
            return response()->json([
                'status' => 200,
                'business_forecast' => $business_forecast
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are Invalid'
            ]);
        }
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
        ->where('business_forecast_name', '=', $request->business_forecast_name)
        ->where('status', '=', $request->status)
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

<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\CallType;
use App\Models\BusinessForecast;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//////////////////////////////////////////////////////////////
//         $status = DB::table('statuses')
//         ->join('call_types_mst','call_types_mst.id','=','business_forecasts.call_type_id')
//         ->join('business_forecasts','business_forecasts.id','=','statuses.business_forecast_id')
//         ->where('call_types_mst.activeStatus','=','Active')
//         ->where('business_forecasts.status','=','Active')
//         ->select('call_types_mst.*','business_forecasts.*','statuses.*')
//         ->orderBy('call_types_mst.name', 'asc')
//         ->orderBy('business_forecasts.business_forecast_name', 'asc')
//         ->orderBy('statuses.status_name', 'asc')
//         ->get();

//         $query = str_replace(array('?'), array('\'%s\''), $status->toSql());
// $query = vsprintf($query, $status->getBindings());
// // dump($query);

// echo $query ;
//////////////////////////////////////////////////////////////////
        $status = DB::table('statuses')
        ->join('call_types_mst','call_types_mst.id','=','business_forecasts.call_type_id')
        ->join('business_forecasts','business_forecasts.id','=','statuses.business_forecast_id')
        ->where('call_types_mst.activeStatus','=','Active')
        ->where('business_forecasts.status','=','Active')
        ->select('call_types_mst.*','business_forecasts.*','statuses.*')
        ->orderBy('call_types_mst.name', 'asc')
        ->orderBy('business_forecasts.business_forecast_name', 'asc')
        ->orderBy('statuses.status_name', 'asc')
        ->get();



        if ($status)
            return response()->json([
                'status' => 200,
                'status' => $status
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
        $status = Status::where('status_name', '=', $request->status_name)->exists();
        if ($status) {
            return response()->json([
                'status' => 400,
                'message' => 'Status Name Already Exists!'
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
        $status_add = Status::firstOrCreate($request->all());
        if ($status_add) {
            return response()->json([
                'status' => 200,
                'message' => 'Status Created Succssfully!'
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
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $status = Status::find($id);
        if ($status)
            return response()->json([
                'status' => 200,
                'status' => $status
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
     * @param  \App\Models\Status  $status
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
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Token::where('tokenid', $request->tokenId)->first();
        if($user['userid'])
        {
        $status = Status::where('call_type_id', '=', $request->call_type_id)
        ->where('business_forecast_name', '=', $request->business_forecast_name)
        ->where('status', '=', $request->status)
        ->where('id', '!=', $id)->exists();
    if ($status) {
        return response()->json([
            'status' => 400,
            'message' => 'Status Name Already Exists!'
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
    
    $status_update = Status::findOrFail($id)->update($request->all());

        if ($status_update)
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
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status_del = Status::destroy($id);
        if ($status_del)
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

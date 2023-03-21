<?php

namespace App\Http\Controllers;

use App\Models\ProcurementType;
use App\Models\CallType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcurementTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $procurement_type = DB::table('procurement_types')
        ->join('call_types_mst','call_types_mst.id','=','procurement_types.call_type_id')
        ->where('call_types_mst.activeStatus','=','Active')
        ->select('call_types_mst.*','procurement_types.*')
        ->orderBy('call_types_mst.name', 'asc')
        ->orderBy('procurement_types.procurement_type_name', 'asc')
        ->get();

        if ($procurement_type)
            return response()->json([
                'status' => 200,
                'procurement_type' => $procurement_type
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
        $procurement_type = ProcurementType::where('procurement_type_name', '=', $request->procurement_type_name)->exists();
        if ($procurement_type) {
            return response()->json([
                'status' => 400,
                'message' => 'Procurement Type Name Already Exists!'
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
        $procurement_type_add = ProcurementType::firstOrCreate($request->all());
        if ($procurement_type_add) {
            return response()->json([
                'status' => 200,
                'message' => 'Procurement Type Created Succssfully!'
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
     * @param  \App\Models\ProcurementType  $procurementType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $procurement_type = ProcurementType::find($id);
        if ($procurement_type)
            return response()->json([
                'status' => 200,
                'procurement_type' => $procurement_type
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
     * @param  \App\Models\ProcurementType  $procurementType
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
     * @param  \App\Models\ProcurementType  $procurementType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Token::where('tokenid', $request->tokenId)->first();
        if($user['userid'])
        {
        $procurement_type_update = ProcurementType::where('call_type_id', '=', $request->call_type_id)
        ->where('procurement_type_name', '=', $request->procurement_type_name)
        ->where('status', '=', $request->status)
        ->where('id', '!=', $id)->exists();
    if ($procurement_type_update) {
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
    
    $procurement_type_update = ProcurementType::findOrFail($id)->update($request->all());

        if ($procurement_type_update)
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
     * @param  \App\Models\ProcurementType  $procurementType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $procurement_type_del = ProcurementType::destroy($id);
        if ($procurement_type_del)
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

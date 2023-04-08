<?php

namespace App\Http\Controllers;

use App\Models\AttendanceType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Token;

class AttendanceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendance_type = AttendanceType::orderBy('created_at', 'desc')->get();

        if ($attendance_type)
            return response()->json([
                'status' => 200,
                'attendancetype' => $attendance_type
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
        //
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
        //return "USER:".$user;
        if($user['userid'])
        {
        $attendance_type = AttendanceType::where('attendanceType', '=', $request->attendanceType)->exists();
        if ($attendance_type) {
            return response()->json([
                'status' => 400,
                'message' => 'Attendance Type Already Exists!'
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
        $attendance_type_add = AttendanceType::firstOrCreate($request->all());
        if ($attendance_type_add) {
            return response()->json([
                'status' => 200,
                'message' => 'Attendance Type Created Succssfully!'
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
     * @param  \App\Models\AttendanceType  $attendanceType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attendance_type = AttendanceType::find($id);
        if ($attendance_type)
            return response()->json([
                'status' => 200,
                'attendancetype' => $attendance_type
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
     * @param  \App\Models\AttendanceType  $attendanceType
     * @return \Illuminate\Http\Response
     */
    public function edit(AttendanceType $attendanceType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AttendanceType  $attendanceType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //return $request;
        $user = Token::where('tokenid', $request->tokenId)->first();
       // return "U:".$user;
        if($user['userid'])
        {
        $attendance_type = AttendanceType::where('attendanceType', '=', $request->attendanceType)
        ->where('activeStatus', '=', $request->activeStatus)
        ->where('id', '!=', $id)->exists();
    if ($call_type) {
        return response()->json([
            'status' => 400,
            'message' => 'Attendance Type Already Exists!'
        ]);
        }
        }
    $request->request->add(['created_by' => $user['userid']]);
    // $validator = Validator::make($request->all(), ['unit_name' => 'required|string', 'unit_status' => 'required']);
    // if ($validator->fails()) {
    //     return response()->json([
    //         'status' => 400,
    //         'message' => $validator->message(),
    //     ]);
    // } 

    $request->request->remove('tokenId');
    
    $attendance_type_update = AttendanceType::findOrFail($id)->update($request->all());

        if ($attendance_type_update)
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
     * @param  \App\Models\AttendanceType  $attendanceType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attendance_type_del = AttendanceType::destroy($id);
        if ($attendance_type_del)
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

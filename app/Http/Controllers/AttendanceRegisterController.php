<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\User;
use App\Models\LeaveRegister;
use App\Models\LeaveRegistersFile;
use Illuminate\Support\Facades\Validator;
use App\Models\AttendanceType;
use Illuminate\Support\Facades\DB;


class AttendanceRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leavereglist = []; 
        $leave_register = LeaveRegister::orderBy('created_at', 'desc')->get();

        foreach($leave_register as $row)
        {
            $userdata = User::where('id',$row->user_id)->first();
            $attendancetype = AttendanceType::where('id',$row->attendance_type_id)->first();
            $leavereglist[] = ['id'=>$row->id,'user_name'=>$userdata->userName,'leavedate'=>$row->from_date,'leavetype'=>$attendancetype->attendanceType,'reason'=>$row->reason]; 

        }
      
    
        if ($leave_register)
            return response()->json([
                'status' => 200,
                'leaveregister' => $leavereglist
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
        // $validator = Validator::make($request->all(), [
        //     'attendance_type_id' => 'required|exists:attendance_types,id',
        //     'from_date' => 'required',
        //     'to_date' => 'required',
        //     'start_time' => 'required|date_format:H:i:s',
        //     'reason' => 'required',
        //     'tokenid' => 'required',
        //     'file' => 'required|array'
        // ]);
    
        // if ($validator->fails()) 
        // {
        //     return response()->json($validator->errors(), 422);
        // }
// return "REQ--".$request->file[0];

        $user = Token::where('tokenid', $request->tokenid)->first();
      
        $userid = $user['userid'];
        if($user)
        {

            $tabel = new LeaveRegister;
            $tabel->user_id = $userid;
            $tabel->attendance_type_id = $request->attendance_type_id;
            $fromdate = date('Y-m-d', strtotime($request->from_date));
            $todate = date('Y-m-d', strtotime($request->from_date));
            $tabel->from_date = $fromdate;
            $tabel->to_date = $todate;
            $tabel->start_time = $request->start_time;
            $tabel->reason = $request->reason;
            $tabel->created_by = $userid;
            $tabel->save();
            $inserted_id = $tabel->id;
            if($inserted_id && $request->hasFile('file'))
            {
                 $file_list = [];
                foreach ($request->file('file') as $file) 
            {
                $call_file_original = $file->getClientOriginalName();
                $call_file_fileName = intval(microtime(true) * 1000) . $call_file_original;
                $file->storeAs('attendanceregisterfiles/', $call_file_fileName, 'public');
                $call_file_mimeType =  $file->getMimeType();
                $call_file_filesize = ($file->getSize()) / 1000;
                $another_table = new LeaveRegistersFile;
                $another_table->mainid = $inserted_id;
                $another_table->filename = $call_file_original;
                $another_table->filetype = $call_file_mimeType;
                $another_table->filesize = $call_file_filesize;
                $another_table->hasfilename = $call_file_fileName;
                $another_table->created_by = $userid;
                $another_table->save();
                
            }
            if($another_table){
                return response()->json([
                    'status' => 200,
                    'message' => 'Attendance FIle Created Successfully!'
                ]);
            }
            
            else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Provided Credentials are Incorrect!'
                ]);
                }
            }


        }
         
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

    public function show($id)
    {
        $show_attendance = DB::table('leave_registers as lr')
         ->join('leave_registers_files as lrf', 'lrf.mainid', 'lr.id')
         ->join('attendance_types as at', 'at.id', 'lr.attendance_type_id')
         ->join('users as u', 'u.id', 'lr.user_id')
       
        ->where('lr.id', $id)
        ->select(
            'lr.id as lrid',
            'lrf.mainid',
            'lr.user_id',
            'u.userName',
            'at.attendanceType',
            'lr.from_date',
            'lr.to_date',
            'lr.start_time',
            'lr.reason',
            'lrf.filename',
        )
        ->latest('lr.id')->first();
       //->get();
    if ($show_attendance)
        return response()->json([
            'status' => 200,
            'showattendance' => $show_attendance
        ]);
    else {
        return response()->json([
            'status' => 404,
            'message' => 'The provided credentials are incorrect.'
        ]);
    }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  
    

/**********************************UPDATE************************************************/
    public function update(Request $request, $id)
    {
    $user = Token::where('tokenid', $request->tokenid)->first(); 
    

    if ($request->hasFile('file')) {
        $file = $request->file('file');
        
        
        $hasFileName = $file->hashName();
        $originalFileName = $file->getClientOriginalName();
        $fileType = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
        
       
        $leaveRegistersFile = LeaveRegistersFile::where('mainid', $id)->first();
        $destinationPath = 'uploads/attendanceregisterfiles/'. $leaveRegistersFile->hasfilename;
        unlink($destinationPath);
        
        $leaveRegistersFile->hasfilename = $hasFileName;
        $leaveRegistersFile->filename = $originalFileName;
        $leaveRegistersFile->filetype = $fileType;
        $leaveRegistersFile->filesize = $fileSize;
        $leaveRegistersFile->save();
        $result = $file->move('uploads/attendanceregisterfiles/',   $leaveRegistersFile->hasfilename);
        
  
    }
    
    
    $attendanceUpdate = LeaveRegister::findOrFail($id)->update($request->all());

    if ($attendanceUpdate) {
        return response()->json([
            'status' => 200,
            'message' => "Updated Successfully!",
        ]);
    } else {
        return response()->json([
            'status' => 400,
            'message' => "Sorry, Failed to Update, Try again later"
        ]);
    }
}
/***************************************************************************************/


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $leaveregister = LeaveRegister::destroy($id);
            if($leaveregister)    
            {return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!"
            ]);}
            else
            {return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.',
                "errormessage" => "",
            ]);}
        }catch(\Illuminate\Database\QueryException $ex){
            $error = $ex->getMessage(); 
            
            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete! This data is used in another file/form/table.',
                "errormessage" => $error,
            ]);
        }
    }

    // public function UserList(Request $request)
    // {
    //     $userlist = [];
    //     $user = Token::where('tokenid', $request->tokenid)->first();   
    //     $userid = $user['userid'];
    //    if($userid){
    //     $userdata = User::where('id',$userid)->first();
    //     $userlist[] =['value'=>$userdata->id,'label'=>$userdata->userName];
    //     return response()->json([
    //         'userlist'=>$userlist
    //     ]);
    //    }
    // }

    public function download($fileName){
      
      $doc = LeaveRegistersFile::find($fileName);
    
        if($doc){
        
           $fileName = $doc['hasfilename'];
            //$file = public_path()."'uploads/attendanceregisterfiles/'".$fileName;
           $file = public_path('uploads/attendanceregisterfiles/'.$fileName);
            // return $file;
            return response()->download($file);
        }
    }

    public function destroyFile($id)
    {
        
        $attendance_file_del = LeaveRegistersFile::destroy($id);
        if ($attendance_file_del)
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

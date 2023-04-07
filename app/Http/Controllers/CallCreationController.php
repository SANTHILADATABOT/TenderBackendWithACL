<?php

namespace App\Http\Controllers;

use App\Models\CallCreation;
// use App\Models\CustomerCreationProfile;
// use App\Models\CallType;
use App\Models\BusinessForecast;
use App\Models\BusinessForeCastStatus;
use App\Models\ProcurementType;
use App\Models\User;
use App\Models\CallLog;
use App\Models\CallFileSub;
// use App\Models\CallLogFiles;
// use App\Models\CallLogFilesSub;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;



class CallCreationController extends Controller
{

    public function index()
    {
        $call_log = DB::table('call_log_creations as clc')
            ->join('customer_creation_profiles as cc', 'cc.id', 'clc.customer_id')
            ->join('call_types_mst as ct', 'ct.id', 'clc.call_type_id')
            ->join('business_forecasts as bf', 'bf.id', 'clc.bizz_forecast_id')
            ->join('business_forecast_statuses as bfs', 'bfs.id', 'clc.bizz_forecast_status_id')
            ->join('users as u', 'u.id', 'clc.executive_id')
            ->join('call_procurement_types as pt', 'pt.id', 'clc.procurement_type_id')
            ->select(
                'clc.callid',
                'cc.id',
                'cc.customer_name',
                'ct.id',
                'ct.name as callname',
                'bf.id',
                'bf.name as bizzname',
                'bfs.id',
                'bfs.status_name as bizzstatusname',
                'u.id',
                'u.name as username',
                'pt.id',
                'pt.name as proname',
                'clc.id',
                'clc.call_date',
                'clc.action',
                'clc.next_followup_date',
                'clc.close_date',
                'clc.additional_info',
                'clc.remarks',
            )
            ->get();
        if ($call_log)
            return response()->json([
                'status' => 200,
                'calllog' => $call_log
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }


    public function store(Request $request)
    {


        $user = Token::where('tokenid', $request->tokenid)->first();
        if ($user['userid']) {
            // $call_log = CallLog::where('customer_id', '=', $request->customer_id)->exists();
            // if ($call_log) {
            // return response()->json([
            //     'status' => 400,
            //     'message' => 'Call Log Already Exists!'
            // ]);
            // } 


            // $curryear = date('y');
            // $currmonth = date('m');
            
            $calldate=explode("-",$request->call_date);
            $curryear = substr($calldate[0],2,4);
            $currmonth = $calldate[1];
            $calseq_qry = CallLog::select('callid')->where('call_date','Like','%'.substr($calldate[0],2,2).'-'.$calldate[1].'%')->orderby('id', 'DESC')->limit(1)->get();

//             $query = str_replace(array('?'), array('\'%s\''), $calseq_qry->toSql());
// $query = vsprintf($query, $calseq_qry->getBindings());
// // dump($query);

// echo $query ;

                $call_id=null;
            if ($calseq_qry->isEmpty()) {
                // echo " - It is Empty - ";
                // $request->request->add(['callid' => "CID-" . $curryear . $currmonth . "00001"]);
                $call_id= "CID-" .substr($calldate[0],2,2).$calldate[1]. "00001";

            } else {
                // echo " - It is in else - ";
                $year = substr($calseq_qry[0]->callid, 4, 2);
                $month = substr($calseq_qry[0]->callid, 6, 2);
                $lastid = substr($calseq_qry[0]->callid, 8, 5);
                if ($year == $curryear) {
                    // echo " - It is in year == curryear - ";
                    if ($month == $currmonth) {
                        // echo " - It is in month == currmonth - ";
                        $call_id= "CID-" . $curryear . $currmonth . str_pad(($lastid + 1), 5, '0', STR_PAD_LEFT);
                        // $request->request->add(['callid' => "CID-" . $curryear . $currmonth . ($lastid + 1)]);
                    } else {
                        // echo " - It is in month == currmonth  Else - ";
                        $call_id= "CID-" . $curryear . $currmonth . "00001";
                        // $request->request->add(['callid' => "CID-" . $curryear . $currmonth . "00001"]);
                    }
                } else {
                    // echo " - It is in year == curryear Else - ";
                    $call_id= "CID-" . substr($calldate[0],2,2).$calldate[1]. "00001";
                    // $request->request->add(['callid' => "CID-" . $curryear . $currmonth . "00001"]);
                }
            }
            $request->request->add(['callid' => $call_id]);
            $request->request->add(['created_by' => $user['userid']]);
            $request->request->remove('tokenid');
            if (!empty($request->next_followup_date)) {
                $request->request->add(['action' => 'next_followup']);
            }
            if (!empty($request->close_date)) {
                $request->request->add(['action' => 'close']);
            }
            
            $call_log_add = CallLog::firstOrCreate($request->all());
            $call_log_add->callid= $call_id;
            $call_log_add->save();
            if ($call_log_add) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Call Log Form Created Succssfully!'
                ]);
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Provided Credentials are Incorrect!'
            ]);
        }
    }


    public function show($id)
    {

        $show_call_log = DB::table('call_log_creations as clc')
            ->join('customer_creation_profiles as cc', 'cc.id', 'clc.customer_id')
            ->join('call_types_mst as ct', 'ct.id', 'clc.call_type_id')
            ->join('business_forecasts as bf', 'bf.id', 'clc.bizz_forecast_id')
            ->join('business_forecast_statuses as bfs', 'bfs.id', 'clc.bizz_forecast_status_id')
            ->join('call_procurement_types as pt', 'pt.id', 'clc.procurement_type_id')
            ->join('users','clc.executive_id','users.id')
            ->where("clc.id", $id)
            ->select(
                'clc.executive_id as user_id',
                'users.userName',
                'cc.id as cust_id',
                'cc.customer_name',
                'ct.id as call_id',
                'ct.name as callname',
                'bf.id as bizz_id',
                'bf.name as bizzname',
                'bfs.id as bizz_status_id',
                'bfs.status_name as bizzstatusname',
                'pt.id as proc_id',
                'pt.name as proname',
                'clc.id',
                'clc.call_date',
                'clc.action',
                'clc.next_followup_date',
                'clc.close_date',
                'clc.additional_info',
                'clc.remarks',
                'clc.close_status_id'
                // 'clc.filename',
            )
            ->get();

        if ($show_call_log)
            return response()->json([
                'status' => 200,
                'showcall' => $show_call_log,
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are Invalid'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $user = Token::where('tokenid', $request->tokenid)->first();
        if ($user['userid']) {
            $call_log = CallLog::findOrFail($id);
            if (!$call_log) {  
                return response()->json([
                    'status' => 400,
                    'message' => 'Invalid Credentials..!'
                ]);
            }
        }
        $request->request->add(['edited_by' => $user['userid']]);
        $request->request->remove('tokenid');
        if (!empty($request->next_followup_date)){
            $request->request->add(['action' => 'next_followup']);
        }
        if (!empty($request->close_date)) {
            $request->request->add(['action' => 'close']);
        }
        
        $call_log_update = CallLog::findOrFail($id)->update($request->all());

        if ($call_log_update)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!",
            ]);
        else {
            return response()->json([
                'status' => 400,
                'message' => "Sorry, Failed to Update, Try again later"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CallCreation  $callCreation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $call_log_del = CallLog::destroy($id);
        if ($call_log_del)
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


    public function getBizzList($callTypeId)
    {

        $business_forecast_list = BusinessForecast::where("call_type_id", $callTypeId)->where("activeStatus", "=", "Active")->get();
        $bizzList = [];
        foreach ($business_forecast_list as $row) {
            $bizzList[] = ["value" => $row['id'], "label" =>  $row['name']];
        }
        return  response()->json([
            'bizzlist' =>  $bizzList,

        ]);
    }

    public function getStatusList($bizzId)
    {
        $status_list = BusinessForeCastStatus::where("bizz_forecast_id", $bizzId)->where("active_status", "=", "Active")->get();
        $statusList = [];
        foreach ($status_list as $row) {
            $statusList[] = ["value" => $row['id'], "label" =>  $row['status_name']];
        }
        return  response()->json([
            'statuslist' =>  $statusList,
        ]);
    }

    public function getProcurementList()
    {
        $procurement_list = ProcurementType::where("active_status", "=", "Active")->get();
        $proList = [];
        foreach ($procurement_list as $row) {
            $proList[] = ["value" => $row['id'], "label" =>  $row['name']];
        }
        return  response()->json([
            'procurementlist' =>  $proList,
        ]);
    }


    public function getUserList()
    {
        $user_list = User::get();
        $userList = [];
        foreach ($user_list as $row) {
            $userList[] = ["value" => $row['id'], "label" =>  $row['name']];
        }
        return  response()->json([
            'user' =>  $userList,
        ]);
    }


    public function download($fileName)
    {

        $doc = CallFileSub::find($fileName);

        if ($doc) {
            $fileName = $doc['hasfilename'];
            //$file = public_path()."'uploads/CallLogs/CallLogFiles/'".$fileName;
            $file = public_path('uploads/CallLogs/CallLogFiles/' . $fileName);
            // return $file;
            return response()->download($file);
        }
    }

    public function callfileupload(Request $request)
    {


        if ($request->hasFile('filename')) {
            $file = $request->file('filename');
            $originalfileName = $file->getClientOriginalName();
            $fileType = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            $hasfileName = $file->hashName();
            $filenameSplited = explode(".", $hasfileName);
            $filename2 = 'calllog' . time() . '.' . $filenameSplited[1];

            // return $filenameSplited;


            if ($filenameSplited[1] != $originalfileName) {
                $fileName = $filenameSplited[0] . "" . $originalfileName;
            } else {
                $fileName = $hasfileName;
            }
            //$file->storeAs('uploads/CallLogs/CallLogFiles/', $fileName, 'public');
            $destinationPath = 'uploads/CallLogs/CallLogFiles/';
            $result = $file->move($destinationPath, $hasfileName);

            $user = Token::where('tokenid', $request->tokenid)->first();
            $request->request->remove('tokenid');


            $get_id = CallLog::orderBy('id', 'desc')->first('id');

            $get = $get_id->id;

            $last_id = $get;


            $callFileSub = new CallFileSub;
            $callFileSub->mainid = $last_id;
            $callFileSub->filename = $filename2;
            $callFileSub->originalfilename = $originalfileName;
            $callFileSub->filetype = $fileType;
            $callFileSub->filesize = $fileSize;
            $callFileSub->hasfilename = $hasfileName;
            $callFileSub->createdby_userid = $user['userid'];
            $callFileSub->save();


            return response()->json([
                'status' => 200,
                'message' => 'Call Log Form Created Succssfully!'
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Provided Credentials are Incorrect!'
            ]);
        }
    }


    // public function calllogfileUpload(Request $request)
    // {

    //     $last_id = $request->fbid;

    //     $file = $request->file('file');
    //     $path = $request->file->getClientOriginalName();
    //     $slipt = explode('.', $path);
    //     $destinationPath = 'uploads/CallLogs/CallLogFiles/';
    //     $new_file_name = 'calllog' . time() . '.' . $slipt[1];
    //     $result = $file->move($destinationPath, $new_file_name);


    //     $user = Token::where('tokenid', $request->tokenid)->first();
    //     // $userid = $user['userid'];
    //     $request->request->remove('tokenid');


    //     if ($user['userid']) {
    //         $Find = CallLogFiles::where('randomno', '=', $request->sub_id)->get();
    //         $count = $Find->count();
    //         if ($count == 0) {

    //             $callLogFiles = new CallLogFiles;
    //             $callLogFiles->cid = $request->cid;
    //             $callLogFiles->date = $request->date;
    // 			$callLogFiles->randomno = $request->sub_id;
    //             $callLogFiles->refrenceno = $request->refrenceno;
    //             $callLogFiles->from = $request->from;
    //             $callLogFiles->to = $request->to;
    // 			$callLogFiles->subject = $request->subject;
    //             $callLogFiles->medium = $request->medium;
    //             $callLogFiles->med_refrenceno = $request->medrefrenceno;


    //             $callLogFiles->createdby_userid = $user['userid'];
    //             $callLogFiles->save();
    //             $get_id = CallLogFiles::orderBy('id', 'desc')
    //                 ->first('id');
    //             $last_id = $callLogFiles->id;


    //             $callLogFilesSub = new CallLogFilesSub;
    //             $callLogFilesSub->randomno = $request->sub_id;
    //             $callLogFilesSub->mainid = $last_id;
    //             $callLogFilesSub->comfile = $new_file_name;
    //             $callLogFilesSub->filetype = $slipt[1];
    //             $callLogFilesSub->createdby_userid = $user['userid'];
    //             $callLogFilesSub->save();


    //         } else {
    //             foreach ($Find as $row) {
    //                 $last_id = $row->id;

    //             }

    //             $callLogFilesSub = new CallLogFilesSub;
    //             $callLogFilesSub->randomno = $request->sub_id;
    //             $callLogFilesSub->mainid = $last_id;
    //             $callLogFilesSub->comfile = $new_file_name;
    //             $callLogFilesSub->filetype = $slipt[1];
    //             $callLogFilesSub->createdby_userid = $user['userid'];
    //             $callLogFilesSub->save();
    //         }


    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Uploaded Succcessfully',


    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 400,
    //             'message' => 'Unable to save!'
    //         ]);
    //     }

    // }

}

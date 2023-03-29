<?php

namespace App\Http\Controllers;

use App\Models\CallCreation;
use App\Models\CustomerCreationProfile;
use App\Models\CallType;
use App\Models\BusinessForecast;
use App\Models\Status;
use App\Models\ProcurementType;
use App\Models\CallLog;
use App\Models\CallLogFiles;
use App\Models\CallLogFilesSub;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;


class CallCreationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        $call_log = DB::table('call_log_creations as clc')
					->join('customer_creation_profiles as cc','cc.id','clc.customer_id')
					->join('call_types_mst as ct','ct.id','clc.call_type_id')
					->join('business_forecasts as bf','bf.id','clc.bizz_forecast_id')
                    ->join('business_forecast_statuses as bfs','bfs.id','clc.bizz_forecast_status_id')
                    ->join('users as u','u.id','clc.executive_id')
					->join('call_procurement_types as pt','pt.id','clc.procurement_type_id')
					->select(
                            
                            'cc.id','cc.customer_name',
							'ct.id','ct.name as callname',
							'bf.id','bf.name as bizzname',
                            'bfs.id','bfs.status_name as bizzstatusname',
                            'u.id','u.name as username',
							'pt.id','pt.name as proname',
                             'clc.id',
							'clc.call_date','clc.action','clc.next_followup_date',
                            'clc.close_date','clc.additional_info','clc.remarks',
							
					)
					->get();

            
// $query = str_replace(array('?'), array('\'%s\''), $call_log->toSql());
// $query = vsprintf($query, $call_log->getBindings());
// return $query;


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

        if($request ->hasFile('filename')){
            $file = $request->file('filename');
            $fileExt = $file->getClientOriginalName();
            $fileName1=$file->hashName();
            $filenameSplited=explode(".",$fileName1);

            if($filenameSplited[1]!=$fileExt)
            {
            $fileName=$filenameSplited[0]."".$fileExt;
            }
            else{
                $fileName=$fileName1;   
            }
            //$file->storeAs('uploads/CallLogs/CallLogFiles/', $fileName, 'public');
            $destinationPath = 'uploads/CallLogs/CallLogFiles/';

           // return "ABC--".$destinationPath;
            $result = $file->move($destinationPath, $fileName);


            $user = Token::where('tokenid', $request->tokenid)->first();  

          //  return "USER:".$user;
            if($user['userid'])
            {
            $call_log = CallLog::where('customer_id', '=', $request->customer_id)->exists();
            if ($call_log) {
            return response()->json([
                'status' => 400,
                'message' => 'call Log Already Exists!'
            ]);
            } 

            $request->request->add(['created_by' => $user['userid']]);
        
            $request->request->remove('tokenid');
            $request->request->add(['filename' => $fileName]);
            $request->request->add(['filetype' => $fileExt]);
           // $request->except(['filename']);
            $call_log_add = CallLog::firstOrCreate($request->all());
            if ($call_log_add) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Call Log Form Created Succssfully!'
                ]);
            }
            }
            }
            else{
            return response()->json([
                'status' => 400,
                'message' => 'Provided Credentials are Incorrect!'
            ]);
            }

    
       // $last_id = $request->fbid;
        // $file = $request->file('filename');
        // $path = $request->file->getClientOriginalName();
        // $slipt = explode('.', $path);
        // $destinationPath = 'uploads/CallLogs/CallLogFiles/';
        // $fileName = 'calllog' . time() . '.' . $slipt[1];
        // $result = $file->move($destinationPath, $fileName);

      
        // return "FILENAME:".$fileName;

        //return "hii123";
    //     $user = Token::where('tokenid', $request->tokenId)->first();
    //     if($user['userid'])
    //     {
    //         $call_log = CallLog::where('customer_id', '=', $request->customer_id)->exists();
    //     if ($call_log) {
    //         return response()->json([
    //             'status' => 400,
    //             'message' => 'call Log Already Exists!'
    //         ]);

    //     }
       
       
    //     $request->request->add(['created_by' => $user['userid']]);
    //     $request->request->add(['filename' => $fileName]);
    //     $request->request->remove('tokenId');
    //     $call_log_add = CallLog::firstOrCreate($request->all());
    //     if ($call_log_add) {
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Call Log Form Created Succssfully!'
    //         ]);
    //     }
    // }
    // else{
    //     return response()->json([
    //         'status' => 400,
    //         'message' => 'Provided Credentials are Incorrect!'
    //     ]);
    // }
    // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CallCreation  $callCreation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $show_call_log = DB::table('call_log_creations as clc')
					->join('customer_creation_profiles as cc','cc.id','clc.customer_id')
					->join('call_types_mst as ct','ct.id','clc.call_type_id')
					->join('business_forecasts as bf','bf.id','clc.bizz_forecast_id')
                    ->join('business_forecast_statuses as bfs','bfs.id','clc.bizz_forecast_status_id')
					->join('call_procurement_types as pt','pt.id','clc.procurement_type_id')
                    ->where("clc.id",$id)
					->select(
                        'cc.id','cc.customer_name',
                        'ct.id','ct.name as callname',
                        'bf.id','bf.name as bizzname',
                        'bfs.id','bfs.status_name as bizzstatusname',
                        'pt.id','pt.name as proname',
                        'clc.id',
                        'clc.call_date','clc.action','clc.next_followup_date',
                        'clc.close_date','clc.additional_info','clc.remarks',
                        'clc.filename',
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CallCreation  $callCreation
     * @return \Illuminate\Http\Response
     */
    public function edit(CallCreation $callCreation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CallCreation  $callCreation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        ////////////////////////////////////////////////
        if($request->hasFile('filename')){
            $file = $request->file('filename');
            $fileExt = $file->getClientOriginalExtension();
            $fileName1=$file->hashName();
            //received File extentions sometimes converted by browsers
            //Have to set orignal file extention before save
            $filenameSplited=explode(".",$fileName1);
            if($filenameSplited[1]!=$fileExt)
            {
            $fileName=$filenameSplited[0].".".$fileExt;
            }
            else{
                $fileName=$fileName1;   
            }
            $file->storeAs('uploads/CallLogs/CallLogFiles/', $fileName, 'public');
            
            
            //to delete Existing Image from storage
            $data = CallLog::find($id);
            $image_path = public_path('uploads/CallLogs/CallLogFiles/').'/'.$data->filepath;
            unlink($image_path);
           
            $user = Token::where("tokenid", $request->tokenId)->first();   
            $request->request->add(['created_by' => $user['userid']]);
            $request->request->remove('tokenId');
            $request->request->add(['filename' => $fileName]);
           // $request->request->add(['filetype' => $fileExt]);
           
            $dataToUpdate = $request->except(['filename']);
            $qcedit = CallLog::findOrFail($id)->update($dataToUpdate);
        if ($qcedit)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!"
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
        else{
            
                $user = Token::where("tokenid", $request->tokenId)->first();  
                
                $request->request->add(['updated_by' => $user['userid']]);
                // $request->request->add(['filepath' => $fileName]);
                $request->request->remove('tokenId');
            
                
            
            $qcedit = CallLog::findOrFail($id)->update($request->all());
            if ($qcedit)
                return response()->json([
                    'status' => 200,
                    'message' => "Updated Successfully!"
                ]);
            else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect.'
                ]);
            }
            }
        ///////////////////////////////////////////////
    //     $file = $request->file('filename');
    //     $path = $request->file->getClientOriginalName();
    //     $slipt = explode('.', $path);
    //     $destinationPath = 'uploads/CallLogs/CallLogFiles/';
    //     $fileName = 'calllog' . time() . '.' . $slipt[1];
    //     $result = $file->move($destinationPath, $fileName);

    //     // return "Orignalname:".$path."--";

    //     $data = CallLog::find($id);
    //     $file_path = public_path('CallLogs/CallLogFiles/').'/'.$data->filename;
    //     unlink($file_path);

    //     // return "FPATH:".$file_path."---";

    //     $user = Token::where('tokenid', $request->tokenId)->first();
    //     if($user['userid'])
    //     {
    //     $call_log = CallLog::where('customer_id', '=', $request->customer_id)
    //     ->where('id', '!=', $id)->exists();
    // if ($call_log) {
    //     return response()->json([
    //         'status' => 400,
    //         'message' => 'Call Log Already Exists!'
    //     ]);
    //     }
    //     }

    // $request->request->add(['updated_by' => $user['userid']]);
    // $request->request->remove('tokenId');
    // $request->request->add(['filepath' => $fileName]);
    
  

    // $call_log_update = CallLog::findOrFail($id)->update($request->all());
    //     if ($call_log_update)
    //         return response()->json([
    //             'status' => 200,
    //             'message' => "Updated Successfully!",
    //         ]);
    //     else{
    //         return response()->json([
    //             'status' => 400,
    //             'message' => "Sorry, Failed to Update, Try again later"
    //         ]);
    //     }
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
     
        $business_forecast_list = BusinessForecast::where("call_type_id",$callTypeId)->where("activeStatus", "=", "Active")->get();
        $bizzList = [];
        foreach($business_forecast_list as $row){
            $bizzList[] = ["value" => $row['id'], "label" =>  $row['name']] ;
        }
        return  response()->json([
            'bizzlist' =>  $bizzList,
            
        ]);
    }

    public function getStatusList($bizzId)
    {
        $status_list = Status::where("bizz_forecast_id",$bizzId)->where("active_status", "=", "Active")->get();
        $statusList = [];
        foreach($status_list as $row){ 
            $statusList[] = ["value" => $row['id'], "label" =>  $row['status_name']] ;
        }
        return  response()->json([
            'statuslist' =>  $statusList,
        ]);
    }

    public function getProcurementList()
    {
        $procurement_list = ProcurementType::where("active_status", "=", "Active")->get();
        $proList = [];
        foreach($procurement_list as $row){
            $proList[] = ["value" => $row['id'], "label" =>  $row['name']] ;
        }
        return  response()->json([
            'procurementlist' =>  $proList,
        ]);
    }

    public function calllogfileUpload(Request $request)
    {

        $last_id = $request->fbid;

        $file = $request->file('file');
        $path = $request->file->getClientOriginalName();
        $slipt = explode('.', $path);
        $destinationPath = 'uploads/CallLogs/CallLogFiles/';
        $new_file_name = 'calllog' . time() . '.' . $slipt[1];
        $result = $file->move($destinationPath, $new_file_name);


        $user = Token::where('tokenid', $request->tokenid)->first();
        // $userid = $user['userid'];
        $request->request->remove('tokenid');


        if ($user['userid']) {
            $Find = CallLogFiles::where('randomno', '=', $request->sub_id)->get();
            $count = $Find->count();
            if ($count == 0) {

                $callLogFiles = new CallLogFiles;
                $callLogFiles->cid = $request->cid;
                $callLogFiles->date = $request->date;
				$callLogFiles->randomno = $request->sub_id;
                $callLogFiles->refrenceno = $request->refrenceno;
                $callLogFiles->from = $request->from;
                $callLogFiles->to = $request->to;
				$callLogFiles->subject = $request->subject;
                $callLogFiles->medium = $request->medium;
                $callLogFiles->med_refrenceno = $request->medrefrenceno;
              
              
                $callLogFiles->createdby_userid = $user['userid'];
                $callLogFiles->save();
                $get_id = CallLogFiles::orderBy('id', 'desc')
                    ->first('id');
                $last_id = $callLogFiles->id;


                $callLogFilesSub = new CallLogFilesSub;
                $callLogFilesSub->randomno = $request->sub_id;
                $callLogFilesSub->mainid = $last_id;
                $callLogFilesSub->comfile = $new_file_name;
                $callLogFilesSub->filetype = $slipt[1];
                $callLogFilesSub->createdby_userid = $user['userid'];
                $callLogFilesSub->save();


            } else {
                foreach ($Find as $row) {
                    $last_id = $row->id;

                }

                $callLogFilesSub = new CallLogFilesSub;
                $callLogFilesSub->randomno = $request->sub_id;
                $callLogFilesSub->mainid = $last_id;
                $callLogFilesSub->comfile = $new_file_name;
                $callLogFilesSub->filetype = $slipt[1];
                $callLogFilesSub->createdby_userid = $user['userid'];
                $callLogFilesSub->save();
            }


            return response()->json([
                'status' => 200,
                'message' => 'Uploaded Succcessfully',


            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }

    }

    public function download($fileName){

        $doc = CallLog::find($fileName);

        if($doc){
            $fileName = $doc['filename'];
            //$file = public_path()."'uploads/CallLogs/CallLogFiles/'".$fileName;
            $file = public_path('uploads/CallLogs/CallLogFiles/'.$fileName);
           // return $file;
            return response()->download($file);
        }
    }
}

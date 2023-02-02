<?php

namespace App\Http\Controllers;

use App\Models\BidManagementWorkOrderCommunicationFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;

class BidManagementWorkOrderCommunicationFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
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

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalfileName = $file->getClientOriginalName();
            $FileExt = $file->getClientOriginalExtension();
            $filenameSplited = explode(".", $originalfileName);
            $hasfileName = $file->hashName();
            $hasfilenameSplited = explode(".", $hasfileName);
            $fileName = $hasfilenameSplited[0] . "." . $filenameSplited[1];


            $user = Token::where('tokenid', $request->tokenid)->first();
            // $userid = $user['userid'];
            $request->request->remove('tokenid');
            if ($user['userid']) {
                $CommunicationFiles = new BidManagementWorkOrderCommunicationFiles;
                $CommunicationFiles->bidid = $request->bidid;
                $CommunicationFiles->date = $request->date;
                $CommunicationFiles->refrenceno = $request->refrenceno;
                $CommunicationFiles->from = $request->from;
                $CommunicationFiles->to = $request->to;
                $CommunicationFiles->subject = $request->bidid;
                $CommunicationFiles->medium = $request->medium;
                $CommunicationFiles->med_refrenceno = $request->medrefrenceno;
                $CommunicationFiles->comfile = $fileName;
                $CommunicationFiles->filetype = $FileExt;
                $CommunicationFiles->createdby_userid = $user['userid'];
                //$CommunicationFiles -> updatedby_userid = 0 ;

                $CommunicationFiles->save();

            }
            $file->storeAs('BidManagement/WorkOrder/CommunicationFiles/', $fileName, 'public');
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BidManagementWorkOrderCommunicationFiles  $bidManagementWorkOrderCommunicationFiles
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $CommunicationFiles = BidManagementWorkOrderCommunicationFiles::where('bidid', '=', $id)->get();
        if ($CommunicationFiles) {
            return response()->json([
                'status' => 200,
                'CommunicationFiles' => $CommunicationFiles,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BidManagementWorkOrderCommunicationFiles  $bidManagementWorkOrderCommunicationFiles
     * @return \Illuminate\Http\Response
     */
    public function edit(BidManagementWorkOrderCommunicationFiles $bidManagementWorkOrderCommunicationFiles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidManagementWorkOrderCommunicationFiles  $bidManagementWorkOrderCommunicationFiles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Token::where("tokenid", $request->tokenid)->first();
        $request->request->add(['updatedby_userid' => $user['userid']]);
        if ($user['userid']) {


            $request->request->remove('tokenid');
            if ($request->hasFile('file')) {

                $file = $request->file('file');
                $originalfileName = $file->getClientOriginalName();
                $FileExt = $file->getClientOriginalExtension();
                $filenameSplited = explode(".", $originalfileName);
                $hasfileName = $file->hashName();
                $hasfilenameSplited = explode(".", $hasfileName);

                $fileName = $hasfilenameSplited[0] . "." . $filenameSplited[1];

                //to delete Existing Image from storage
                $data = BidManagementWorkOrderCommunicationFiles::where("id", "=", $id)->select("*")->get();

                $image_path = public_path('uploads/BidManagement/WorkOrder/CommunicationFiles') . '/' . $data[0]->comfile;
                // $image_path = public_path('uploads/BidManagement/WorkOrder/CommunicationFiles').'/MwT5orH0qO9KxKSSCSHNVNgdByc2JK3IWUWeAd51.jpg';

                $path = str_replace("\\", "/", $image_path);
                unlink($path);
                $file->storeAs('BidManagement/WorkOrder/CommunicationFiles/', $fileName, 'public');


                $request->request->add(['comfile' => $fileName]);
                $request->request->add(['filetype' => $FileExt]);
            }
            $dataToUpdate = $request->except(['file', '_method']);
            $qcedit = BidManagementWorkOrderCommunicationFiles::where("id", $id)->update($dataToUpdate);

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

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BidManagementWorkOrderCommunicationFiles  $bidManagementWorkOrderCommunicationFiles
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            //to delete Existing Image from storage
            $data = BidManagementWorkOrderCommunicationFiles::find($id);

            $image_path = public_path() . "/uploads/BidManagement/WorkOrder/CommunicationFiles/" . $data->comfile;
            unlink($image_path);

            $comm = BidManagementWorkOrderCommunicationFiles::destroy($id);
            if ($comm) {
                return response()->json([
                    'status' => 200,
                    'message' => "Deleted Successfully!",
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect!?',
                    "errormessage" => "",
                ]);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();
            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete! This data is used in another file/form/table.',
                "errormessage" => $error,
            ]);
        }
    }

    public function getComList($id)
    {

        $CommunicationFiles = BidManagementWorkOrderCommunicationFiles::where('bidid', '=', $id)->get();
        if ($CommunicationFiles) {
            return response()->json([
                'status' => 200,
                'comm' => $CommunicationFiles,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\CompetitorDetailsWorkOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


class CompetitorDetailsWorkOrderController extends Controller
{

    public function store(Request $request)
    {

        if ($request->hasFile('woFile')) {

            $woFile = $request->woFile;
            $woFileExt = $woFile->getClientOriginalExtension();
            //received File extentions sometimes converted by browsers
            //Have to set orignal file extention before save
            $woFileName1 = $woFile->hashName();
            $woFilenameSplited = explode(".", $woFileName1);
            if ($woFilenameSplited[1] != $woFileExt) {
                $woFileName = $woFilenameSplited[0] . "." . $woFileExt;
            } else {
                $woFileName = $woFileName1;
            }
            $woFile->storeAs('competitor/woFile', $woFileName, 'public');
            $user = Token::where("tokenid", $request->tokenId)->first();
            $request->request->add(['cr_userid' => $user['userid']]);
            // $request->request->remove('woFile');
            // $request->request->add(['woFile' => $woFileName]);
            $request->request->remove('tokenId');
            $request->request->add(['woFileType' => $woFileExt]);
            // $request->woFile=$woFileName;


            // if($request->completionFile!=''){

            if ($request->hasFile('completionFile')) {

                $completionFile = $request->completionFile;
                $completionFileExt = $completionFile->getClientOriginalExtension();
                //received File extentions sometimes converted by browsers
                //Have to set orignal file extention before save
                $completionFileName1 = $completionFile->hashName();
                $completionFilenameSplited = explode(".", $completionFileName1);
                if ($completionFilenameSplited[1] != $completionFileExt) {
                    $completionFileName = $completionFilenameSplited[0] . "." . $completionFileExt;
                } else {
                    $completionFileName = $completionFileName1;
                }
                $completionFile->storeAs('competitor/woCompletionFile', $completionFileName, 'public');
                // $request->completionFile= $completionFileName;
                // $request->request->add(['completionFileType' => $completionFileExt]);
            } else {
                $completionFileExt = '';
                $completionFileName = '';
            }


            $existence = CompetitorDetailsWorkOrder::where("compNo", $request->compNo)
                ->where("compId", $request->compId)
                ->where("custName", $request->custName)->exists();

            if ($existence) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Certificate Name Already Exists!'
                ]);
            }

            $validator = Validator::make($request->all(), ['compId' => 'required|integer', 'compNo' => 'required|string', 'custName' => 'required|string', 'projectName' => 'required|string', 'tnederId' => 'required|string', 'state' => 'required|string', 'woDate' => 'required|date', 'quantity' => 'required|string', 'unit' => 'required|string', 'projectValue' => 'required|string', 'perTonRate' => 'required|string', 'qualityCompleted' => 'required|string', 'date' => 'required|date', 'cr_userid' => 'required|integer']);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 404,
                    'message' => "Not able to Add details now..!",
                    'error' => $validator->messages(),
                ]);
            }
            // $datatostore=$request->except(['woFile','completionFile']);
            // echo $request->compId;
            $datatostore = new CompetitorDetailsWorkOrder;
            $datatostore->compId = $request->compId;
            $datatostore->compNo = $request->compNo;
            $datatostore->projectName = $request->projectName;
            $datatostore->custName = $request->custName;
            $datatostore->tnederId = $request->tnederId;
            $datatostore->state = $request->state;
            $datatostore->woDate = $request->woDate;
            $datatostore->quantity = $request->quantity;
            $datatostore->unit = $request->unit;
            $datatostore->projectValue = $request->projectValue;
            $datatostore->perTonRate = $request->perTonRate;
            $datatostore->qualityCompleted = $request->qualityCompleted;
            $datatostore->date = $request->date;
            $datatostore->cr_userid = $user['userid'];
            $datatostore->woFile = $woFileName;
            $datatostore->woFileType = $woFileExt;
            $datatostore->completionFile = $completionFileName;
            $datatostore->completionFileType = $completionFileExt;
            $woRes = $datatostore->save();
            if ($woRes) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Added Succssfully!',
                ]);
            }
        } else {

            return response()->json([
                'status' => 404,
                'message' => 'Unable to save!'
            ]);
        }
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $data = CompetitorDetailsWorkOrder::find($id); //to handle existing images 
        $datatostore =  CompetitorDetailsWorkOrder::findOrFail($id);

        if ($request->hasFile('woFile')) {
            // echo "Wo FIle <br>     ";
            //Update WO File
            $woFile = $request->woFile;
            $woFileExt = $woFile->getClientOriginalExtension();
            //received File extentions sometimes converted by browsers
            //Have to set orignal file extention before save
            $woFileName1 = $woFile->hashName();
            $woFilenameSplited = explode(".", $woFileName1);
            if ($woFilenameSplited[1] != $woFileExt) {
                $woFileName = $woFilenameSplited[0] . "." . $woFileExt;
            } else {
                $woFileName = $woFileName1;
            }
            $woFile->storeAs('competitor/woFile', $woFileName, 'public');
            $datatostore['woFile'] = $woFileName;
            $datatostore['woFileType'] = $woFileExt;

            //to delete Existing Image from storage, if image Updated 
            if ($data['woFile']) {
                $image_path = public_path() . "/uploads/competitor/woFile/" . $data->woFile;
                unlink($image_path);
            }
        } else {
            // echo "No Wo FIle  <br>     ";
            if ($data['woFile'] == '' || $data['woFile'] == null) {
                return response()->json([
                    'status' => 404,
                    'message' => "Wo file is Missing, Add it before submit..!",
                ]);
            }
        }
        //Update completionFile
        if ($request->hasFile('completionFile') && $request->completionFile != "removed") {
            // echo "Has WoCompletion FIle  <br>     ";
            $completionFile = $request->completionFile;
            $completionFileExt = $completionFile->getClientOriginalExtension();
            //received File extentions sometimes converted by browsers
            //Have to set orignal file extention before save
            $completionFileName1 = $completionFile->hashName();
            $completionFilenameSplited = explode(".", $completionFileName1);
            if ($completionFilenameSplited[1] != $completionFileExt) {
                $completionFileName = $completionFilenameSplited[0] . "." . $completionFileExt;
            } else {
                $completionFileName = $completionFileName1;
            }
            $completionFile->storeAs('competitor/woCompletionFile', $completionFileName, 'public');
            // $request->completionFile= $completionFileName;
            // $request->request->add(['completionFileType' => $completionFileExt]);
            $datatostore['completionFile'] = $completionFileName;
            $datatostore['completionFileType'] = $completionFileExt;


            //to delete Existing Image from storage, if image Updated
            if ($data['completionFile']) {
                $image_path = public_path() . "/uploads/competitor/woCompletionFile/" . $data->completionFile;
                unlink($image_path);
            }
        } else {
            // echo "Not have WoCompletion FIle  <br>     ";    
            if (($data['completionFile'] != '' || $data['completionFile'] != null) && $request->completionFile == "removed") {
                $datatostore['completionFile'] = "";
                $datatostore['completionFileType'] = "";
                $image_path = public_path() . "/uploads/competitor/woCompletionFile/" . $data->completionFile;
                unlink($image_path);
            }
        }


        $user = Token::where("tokenid", $request->tokenId)->first();
        $request->request->add(['edited_userid' => $user['userid']]);
        $validator = Validator::make($request->all(), ['compId' => 'required|integer', 'compNo' => 'required|string', 'custName' => 'required|string', 'projectName' => 'required|string', 'tnederId' => 'required|string', 'state' => 'required|integer', 'woDate' => 'required|date', 'quantity' => 'required|string', 'unit' => 'required|integer', 'projectValue' => 'required|string', 'perTonRate' => 'required|string', 'qualityCompleted' => 'required|string', 'date' => 'required|date', 'edited_userid' => 'required|integer']);

        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' => "Not able to update details now..!",
                'error' => $validator->messages(),
            ]);
        }


        // $datatostore =  CompetitorDetailsWorkOrder::findOrFail($id);  // declared at the top to set files
        $datatostore['compId'] = $request->compId;
        $datatostore['compNo'] = $request->compNo;
        $datatostore['projectName'] = $request->projectName;
        $datatostore['custName'] = $request->custName;
        $datatostore['tnederId'] = $request->tnederId;
        $datatostore['state'] = $request->state;
        $datatostore['woDate'] = $request->woDate;
        $datatostore['quantity'] = $request->quantity;
        $datatostore['unit'] = $request->unit;
        $datatostore['projectValue'] = $request->projectValue;
        $datatostore['perTonRate'] = $request->perTonRate;
        $datatostore['qualityCompleted'] = $request->qualityCompleted;
        $datatostore['date'] = $request->date;
        $datatostore['edited_userid'] = $user['userid'];
        $woedit = $datatostore->save();


        if ($woedit)
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



    public function destroy($id)
    {
        try {

            //to delete Existing Image from storage
            $data = CompetitorDetailsWorkOrder::find($id);
            //to delete Existing Image from storage
            $data = CompetitorDetailsWorkOrder::find($id);
            $image_path = public_path() . "/uploads/competitor/woCompletionFile/" . $data->completionFile;
            unlink($image_path);

            $image_path = public_path() . "/uploads/competitor/woFile/" . $data->woFile;
            unlink($image_path);

            $wo = CompetitorDetailsWorkOrder::destroy($id);
            if ($wo) {
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



    public function getWOList($compid)
    {
        $wo = CompetitorDetailsWorkOrder::where("compId", $compid)
            ->select('competitor_details_work_orders.*', 'state_masters.state_name', 'unit_masters.unit_name')
            ->join('state_masters', 'state_masters.id', 'competitor_details_work_orders.state')
            ->join('unit_masters', 'unit_masters.id', 'competitor_details_work_orders.unit')
            ->get();
        if ($wo) {
            return response()->json([
                'status' => 200,
                'wo' => $wo
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function download($id, $type)
    {

        $doc = CompetitorDetailsWorkOrder::where('id', $id)
            ->select($type == "woCompletionFile" ? "completionFile" : $type)
            ->get();

        if (!empty($doc[0][$type == "woCompletionFile" ? "completionFile" : $type])) {
            $ext = explode(".", ($doc[0][$type == "woCompletionFile" ? "completionFile" : $type]));
            $contentType = $this->getFileType($ext[1]);
            $file = public_path() . "/uploads/competitor/$type/" . $doc[0][$type == "woCompletionFile" ? "completionFile" : $type];
            
            return response()->download($file, $doc[0][$type == "woCompletionFile" ? "completionFile" : $type], ['Content-Type' => $contentType]);
        } else {
            return response()->json([
                'file' => 'File not found.'
            ], 204);
        }
    }

    public function getFileType($type)
    {
        // Get the file path based on the type
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf',
            'webp' => 'image/webp',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'csv' => 'text/csv',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'rar' => 'application/x-rar-compressed',
            'zip' => 'application/zip',
        ];
    
        return $mimeTypes[$type] ?? 'application/octet-stream';
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\TenderStatusTechEvaluation;
use Illuminate\Support\Facades\DB;
use App\Models\TenderStatusTechEvaluationSub;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TenderStatusTechEvaluationController extends Controller
{

    public function store(Request $request)
    {
        try {
            $user = Token::where("tokenid", $request->tokenid)->first();
            if ($user['userid']) {
                $isBididExist = TenderStatusTechEvaluation::where("bidid", $request->bid_creation_mainid)->first();
                if(!$isBididExist)
                {
                // $validator = Validator::make($request->all(), ['organisation' => 'required|string', 'customername' => 'required|integer',  'tendertype' => 'required|integer', 'nitdate'=>'required', 'cr_userid'=>'required']);
                // if ($validator->fails()) {
                //     return response()->json([
                //         'status' => 400,
                //         'errors' => $validator->messages(),
                //     ]);
                // }

                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $filename_original = $file->getClientOriginalName();
                    $fileName1 = intval(microtime(true) * 1000) . $filename_original;
                    $ext =  $file->getClientOriginalExtension();
                    $filenameSplited = explode(".", $fileName1);
                    if ($filenameSplited[1] != $ext) {
                        $fileName = $filenameSplited[0] . "." . $ext;
                    } else {
                        $fileName = $fileName1;
                    }
                    $file->storeAs('BidManagement/techevaluation', $fileName, 'public');
                }

                $techEval = new TenderStatusTechEvaluation;
                $techEval->bidid = $request->bid_creation_mainid;
                $techEval->evaluationDate = $request->date;
                $techEval->document = $fileName;
                $techEval->created_userid = $user['userid'];
                $techEval->save();

                if ($techEval->id) {
                    foreach ($request->input as $key => $value) {
                        $insertsub = new TenderStatusTechEvaluationSub;
                        $insertsub->techMainId = $techEval->id;
                        $insertsub->created_userid = $user['userid'];
                        $insertsub->competitorId = $key;
                        foreach ($request->input[$key] as $key1 => $value1) {
                            if ($key1 == "status") {
                                $insertsub->qualifiedStatus = $value1;
                            } else if ($key1 == "reason") {
                                $insertsub->reason = $value1;
                            }
                        }
                        $insertsub->save();
                    }
                }


                if ($techEval && $insertsub) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Technial Evaluation Status Added..!'
                    ]);
                } else if ($techEval && !$insertsub) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Oops, Unable to Add..!',
                        'err' => 'not able to insert into sub table'
                    ]);
                } else if (!$techEval && $insertsub) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Oops, Unable to Add..!',
                        'err' => 'not able to insert into main table'
                    ]);
                }
            }
            else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Technical Evalution Details Already Exist For this Tender ..!',
                ]);
            }
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect!',
                'error' => $error
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TenderStatusTechEvaluation  $tenderStatusTechEvaluation
     * @return \Illuminate\Http\Response
     */
    public function show(TenderStatusTechEvaluation $tenderStatusTechEvaluation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TenderStatusTechEvaluation  $tenderStatusTechEvaluation
     * @return \Illuminate\Http\Response
     */
    public function edit(TenderStatusTechEvaluation $tenderStatusTechEvaluation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TenderStatusTechEvaluation  $tenderStatusTechEvaluation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$mainId)
    {
        try {
            $user = Token::where("tokenid", $request->tokenid)->first();
            if ($user['userid']) {
                
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $filename_original = $file->getClientOriginalName();
                    $fileName1 = intval(microtime(true) * 1000) . $filename_original;
                    $ext =  $file->getClientOriginalExtension();
                    $filenameSplited = explode(".", $fileName1);
                    if ($filenameSplited[1] != $ext) {
                        $fileName = $filenameSplited[0] . "." . $ext;
                    } else {
                        $fileName = $fileName1;
                    }

                //to delete existin image    
                $getExistingData = TenderStatusTechEvaluation::find("id", $mainId)->first();
                $image_path = public_path('uploads/BidManagement/techevaluation') . '/' . $getExistingData[0]->document;
                $path = str_replace("\\", "/", $image_path);
                unlink($path);
                    $file->storeAs('BidManagement/techevaluation', $fileName, 'public');
                }

                $techEval = new TenderStatusTechEvaluation;
                $techEval->bidid = $request->bid_creation_mainid;
                $techEval->evaluationDate = $request->date;
                $techEval->document = $fileName;
                $techEval->edited_userid = $user['userid'];
                $techEval->save();

                if ($techEval->id) {
                    foreach ($request->input as $key => $value) {
                        $insertsub = new TenderStatusTechEvaluationSub;
                        $insertsub->techMainId = $techEval->id;
                        $insertsub->edited_userid = $user['userid'];
                        $insertsub->competitorId = $key;
                        foreach ($request->input[$key] as $key1 => $value1) {
                            if ($key1 == "status") {
                                $insertsub->qualifiedStatus = $value1;
                            } else if ($key1 == "reason") {
                                $insertsub->reason = $value1;
                            }
                        }
                        $insertsub->save();
                    }
                }


                if ($techEval && $insertsub) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Technial Evaluation Status Added..!'
                    ]);
                } else if ($techEval && !$insertsub) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Oops, Unable to Add..!',
                        'err' => 'not able to insert into sub table'
                    ]);
                } else if (!$techEval && $insertsub) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Oops, Unable to Add..!',
                        'err' => 'not able to insert into main table'
                    ]);
                }
            }
           
            }
         catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect!',
                'error' => $error
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TenderStatusTechEvaluation  $tenderStatusTechEvaluation
     * @return \Illuminate\Http\Response
     */
    public function destroy(TenderStatusTechEvaluation $tenderStatusTechEvaluation)
    {
        //
    }

    public function getTechEvaluationList($id)
    {
        $fetchresult = DB::table('tender_status_tech_evaluations as main')
            ->where('main.bidid', $id)
            ->join('tender_status_tech_evaluations_subs as sub', "main.id", 'sub.techMainId')
            ->select('main.evaluationDate', 'main.document', 'main.id', 'sub.competitorId', 'sub.qualifiedStatus', 'sub.reason')
            ->get();
        if ($fetchresult)
            return response()->json([
                'status' => 200,
                'result' => $fetchresult->except(['evaluationDate','document']),
                'date'=> $fetchresult[0]->evaluationDate,
                'mainId'=> $fetchresult[0]->id,
                'filename'=> $fetchresult[0]->document,
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function download($id)
    {
        $doc = TenderStatusTechEvaluation::where('bidid',$id)
        ->select("document")
        ->get();
        // $query = str_replace(array('?'), array('\'%s\''), $doc->toSql());
        // $query = vsprintf($query, $doc->getBindings());

        
        if ($doc) {
            $file = public_path() . "/uploads/BidManagement/techevaluation/" . $doc[0]['document'];
            return response()->download($file);
        }
    }

    public function getQualifiedList(){

        $qualifiedList = DB::table('tender_status_tech_evaluations_subs')
        ->join('competitor_profile_creations','tender_status_tech_evaluations_subs.competitorId','competitor_profile_creations.id')
        ->where('tender_status_tech_evaluations_subs.qualifiedStatus', 'qualified')
        ->select('tender_status_tech_evaluations_subs.id','tender_status_tech_evaluations_subs.techMainId','tender_status_tech_evaluations_subs.competitorId', 'tender_status_tech_evaluations_subs.qualifiedStatus', 'tender_status_tech_evaluations_subs.reason', 'competitor_profile_creations.compName') 
        ->orderBy('tender_status_tech_evaluations_subs.id', 'asc')       
        ->get();

        if($qualifiedList){
            return response()->json([
                'qualifiedList' => $qualifiedList
            ]);
        }else{
            return response()->json([
                'qualifiedList' => []
            ]);
        }
    
    }
}


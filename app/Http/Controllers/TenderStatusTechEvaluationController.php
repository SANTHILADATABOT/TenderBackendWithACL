<?php

namespace App\Http\Controllers;

use App\Models\TenderStatusTechEvaluation;
use Illuminate\Support\Facades\DB;
use App\Models\TenderStatusTechEvaluationSub;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;


class TenderStatusTechEvaluationController extends Controller
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
        //return $request->input['8'];
        // try {
            // $user = Token::where("tokenid", $request->tokenId)->first();
           

            // if ($user['userid']) {
            //     // $validator = Validator::make($request->all(), ['organisation' => 'required|string', 'customername' => 'required|integer',  'tendertype' => 'required|integer', 'nitdate'=>'required', 'cr_userid'=>'required']);
            //     // if ($validator->fails()) {
            //     //     return response()->json([
            //     //         'status' => 400,
            //     //         'errors' => $validator->messages(),
            //     //     ]);
            //     // }


            //     if ($request->hasFile('file')) {
            //         $file = $request->file('file');
            //         $filename_original = $file->getClientOriginalName();
            //         $fileName1 = intval(microtime(true) * 1000) . $filename_original;
            //         // $mimeType =  $file->getMimeType();
            //         // $filesize = ($file->getSize()) / 1000;
            //         $ext =  $file->getClientOriginalExtension();
            //         $filenameSplited = explode(".", $fileName1);
            //         if ($filenameSplited[1] != $ext) {
            //             $fileName = $filenameSplited[0] . "." . $ext;
            //         } else {
            //             $fileName = $fileName1;
            //         }
            //         $file->storeAs('BidManagement/techevaluation', $fileName, 'public');
            //     }

            //     $techEval = new TenderStatusTechEvaluation;
            //     $techEval->bidderId = $request->bid_creation_mainid;
            //     $techEval->evaluationDate = $request->date;
            //     $techEval->document = $fileName;
            //     $techEval->created_userid = $user['userid'];
            //     $techEval->save();
                

                $insertsub= new TenderStatusTechEvaluationSub;
                // $insertsub->techMainId = $techEval->id;
            //     $insertsub->competitorId = $request->bid_creation_mainid;
            //     $insertsub->created_userid = $user['userid'];
                foreach($request->input as $key=>$value)
                {
                    foreach($request->input[$key] as $key1=>$value1)
                    {
                        $insertsub->competitorId = $key1;
                        echo " Key : $key1  --- Value: $value1";
                        if($key1=="status")
                        {
                            $insertsub->qualifiedStatus = $value1;
                        }
                        else if($key1=="reason")
                        {
                            $insertsub->reason = $value1;
                        }
                        
                    }
                        
                }
                return $insertsub->all();
                


            //     if ($techEval) {
            //         return response()->json([
            //             'status' => 200,
            //             'message' => 'Technial Evaluation Status Added..!'
            //         ]);
            //     }
            // }
        // } catch (\Exception $e) {
        //     $error = $e->getMessage();
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'The provided credentials are incorrect!',
        //         'error' => $error
        //     ]);
        // }
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
    public function update(Request $request, TenderStatusTechEvaluation $tenderStatusTechEvaluation)
    {
        //
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

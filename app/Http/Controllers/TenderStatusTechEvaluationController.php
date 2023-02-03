<?php

namespace App\Http\Controllers;

use App\Models\TenderStatusTechEvaluation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
        //
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

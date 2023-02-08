<?php

namespace App\Http\Controllers;

use App\Models\TenderStatusContractAwarded;
use Illuminate\Http\Request;

class TenderStatusContractAwardedController extends Controller
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
        return $request;
        //
          //get the user id 
        //   $user = Token::where('tokenid', $request->tokenid)->first();   
        //   $userid = $user['userid'];
        //   $financialEvaluation = null;
        //   $updatearray = [];
        //   if($userid){


              
        //   }
      
          
            //   return response()->json([
            //       'status' => 'success',
            //       'msg' => 'Submitted successfully',
            //   ]);
          
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TenderStatusContractAwarded  $tenderStatusContractAwarded
     * @return \Illuminate\Http\Response
     */
    public function show(TenderStatusContractAwarded $tenderStatusContractAwarded)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TenderStatusContractAwarded  $tenderStatusContractAwarded
     * @return \Illuminate\Http\Response
     */
    public function edit(TenderStatusContractAwarded $tenderStatusContractAwarded)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TenderStatusContractAwarded  $tenderStatusContractAwarded
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TenderStatusContractAwarded $tenderStatusContractAwarded)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TenderStatusContractAwarded  $tenderStatusContractAwarded
     * @return \Illuminate\Http\Response
     */
    public function destroy(TenderStatusContractAwarded $tenderStatusContractAwarded)
    {
        //
    }
    public function download($id)
    {
        $doc = TenderStatusContractAwarded::where('bidid',$id)
        ->select("document")
        ->get();
        
        if ($doc) {
            $file = public_path() . "/uploads/BidManagement/techevaluation/". $doc[0]['document'];
            return response()->download($file,$doc[0]['document']);
        }
    }
}

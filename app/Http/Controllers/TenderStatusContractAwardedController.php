<?php

namespace App\Http\Controllers;

use App\Models\TenderStatusContractAwarded;
use Illuminate\Http\Request;
use App\Models\Token;
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
        try {
        $user = Token::where("tokenid", $request->tokenid)->first();
        if ($user['userid']) {
            $isBididExist = TenderStatusContractAwarded::where("bidid", $request->bid_creation_mainid)->first();
            if (!$isBididExist) {

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
                    $file->storeAs('BidManagement/tenderawarded', $fileName, 'public');
                }
                $awarded = new TenderStatusContractAwarded;
                $awarded->bidid = $request->bid_creation_mainid;
                $awarded->competitorId  = $request->competitorId;
                $awarded->contactAwardedDate = $request->date;
                $awarded->document = $fileName;
                $awarded->description =$request->description;
                $awarded->created_userid = $user['userid'];
                $awarded->save();
    
                if ($awarded) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'AWARD OF CONTRACT Added..!'
                    ]);
                }else{
                    return response()->json([
                        'status' => 400,
                        'message' => 'Oops, Unable to Add..!',
                        'err' => 'not able to insert into sub table'
                    ]);
                }
            }}
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect!',
                'error' => $error
            ]);
        }



        }
      
        

       
        
   
    public function insert(Request $request)
    {
        echo "insert Function";
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

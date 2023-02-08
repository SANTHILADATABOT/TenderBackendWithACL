<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenderStatusBidders;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;
use Illuminate\Support\Facades\DB;

class TenderStatusBiddersController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user = Token::where("tokenid", $request->tokenId)->first();

            //  $validator = Validator::make($request->all(), ['bidid' => 'required|integer','bidders' => 'required|integer','created_userid'=>'required|integer']);
            //  if ($validator->fails()) {
            //      return response()->json([
            //          'status' => 404,
            //          // 'message' =>"Not able to Add Strength/Weakness details now..!",
            //          'message' => $validator->messages(),
            //      ]);
            //  }
            if ($user['userid']) {

                foreach ($request->input as $key => $value) {
                    $bidders = new TenderStatusBidders;
                    $bidders->bidid = $request->bidid;
                    $bidders->created_userid = $user['userid'];
                    foreach ($request->input[$key] as $key1 => $value1) {
                        if ($key1 == "compId") {
                            $bidders->competitorId = $value1['value'];
                        } else if ($key1 == "status") {
                            $bidders->acceptedStatus = $value1;
                        } else if ($key1 == "reason") {
                            $bidders->reason = $value1;
                        }
                    }
                    $bidders->save();
                }




                //  $bidders =new TenderStatusBidders;
                //  $bidders->bidid=$request->bidid;
                // //  $bidders->no_of_bidders=$request->bidders;
                // $bidders->input = 
                //  $bidders->created_userid=$request->created_userid;
                //  $bidders->save();
                //  //$bidders = TenderStatusBidders::firstOrCreate($request->all());
                if ($bidders) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Added Succssfully!',
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

    //Get and Retunr All the Bidders
    public function show($id)
    {
        $bidders = TenderStatusBidders::where("bidid", $id)
            ->select('bidid', 'competitorId', 'acceptedStatus', 'reason')
            ->get();
        if ($bidders) {
            return response()->json([
                'status' => 200,
                'bidders' => $bidders
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }


    public function edit($id)
    {
        echo "Edit Function";
    }


    public function update(Request $request, $id)
    {
        try {
            $user = Token::where("tokenid", $request->tokenId)->first();
            // $request->request->add(['edited_userid' => $user['userid']]);
            // $request->request->remove('tokenId');
            // $request->request->add(['no_of_bidders' => $request->bidders]);
            // $request->request->remove('bidders');

            // $validator = Validator::make($request->all(), ['bidid' => 'required|integer','no_of_bidders' => 'required|integer','edited_userid'=>'required|integer']);

            // if ($validator->fails()) {
            //     return response()->json([
            //         'status' => 404,
            //         'errors' => $validator->messages(),
            //     ]);
            // }
            
            if ($user['userid']) {
                foreach ($request->input as $key => $value) {
                    foreach ($request->input[$key] as $key1 => $value1) {
                        $bidders = TenderStatusBidders::where("competitorId", (int)$value1['value'])
                        ->where("bidid",$request->bidid)->get();
                        $bidders->bidid = $request->bidid;
                        $bidders->updated_userid = $user['userid'];
                        if ($key1 == "compId") {

                            $bidders->competitorId = $value1['value'];
                        } else if ($key1 == "status") {
                            $bidders->acceptedStatus = $value1;
                        } else if ($key1 == "reason") {
                            $bidders->reason = $value1;
                        }
                    }
                    $bidders->save();
                }


                // $bidders = TenderStatusBidders::where("bidid",$id)->update($request->all());
                if ($bidders)
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
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect!',
                'error' => $error
            ]);
        }
    }


    public function getBidders($id)
    {

        $bidders = TenderStatusBidders::where("bidid", $id)
            ->select("*")
            ->get()->first();
        if ($bidders) {
            return response()->json([
                'status' => 200,
                'bidders' => $bidders
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
    public function updateStatus(Request $request, $id)
    {
        $user = Token::where("tokenid", $request->tokenId)->first();
        $request->request->add(['edited_userid' => $user['userid']]);
        $request->request->remove('tokenId');

        if ($user['userid']) {
            $bidders = TenderStatusBidders::where("bidid", $id)->update($request->all());
            if ($bidders)
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


    public function getAcceptedBidders($id)
    {

        $bidders = DB::table('tender_status_bidders')
            ->where("bidid", $id)
            ->select("tender_status_bidders.id", "tender_status_bidders.bidid", "tender_status_bidders.competitorId", "competitor_profile_creations.compName")
            ->join("competitor_profile_creations", "tender_status_bidders.competitorId", "competitor_profile_creations.id")
            ->where("acceptedStatus", "approved")
            ->get();
        if ($bidders) {
            return response()->json([
                'status' => 200,
                'bidders' => $bidders
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function BiddersTenderStatus(Request $request, $id)
    {
        echo $request->competitorId;
        // $bidders =new TenderStatusBidders;
        // $bidders->bidid=$id;
        // $bidders->competitorId='9';
        // $bidders->acceptedStatus='approved';
        // $bidders->created_userid=$id;
        // $bidders->save();
        // if ($bidders) {
        //     return response()->json([
        //         'status' => 200,
        //         'message' => 'Added Succssfully!',
        //     ]);
        // }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\calltobdm;
use App\Models\calltobdm_has_customer;
use App\Models\CustomerCreationProfile;
use App\Models\User;
use App\Models\Token;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CalltobdmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $callToBdmList = calltobdm::with('user', 'customer.customer')->get();

        if ($callToBdmList)
        return response()->json([
            'status' => 200,
            'callToBdmList' => $callToBdmList
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
        //
         //get the user id
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if($userid){
            $calltobdm = new calltobdm;
            $calltobdm->user_id = $request->staffName;
            $calltobdm->created_userid  =   $userid;
            $calltobdm->edited_userid   =   null;
            $calltobdm->save();
        } 

        if ($calltobdm) {

          
            foreach($request->customer as $customer){
                $cus = new calltobdm_has_customer;
                $cus->calltobdm_id =  $calltobdm['id'];
                $cus->customer_id =  $customer['value'];
                $cus->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'New Call to BDM Assigned Successfully',
                'id' => $calltobdm['id'],
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\calltobdm  $calltobdm
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $calltobdm = calltobdm::find($id);
      
        if ($calltobdm){

            $user = User::find($calltobdm['user_id']);
            $customers = calltobdm_has_customer::where('calltobdm_id', $calltobdm['id'])->get();

            $calltobdm['user_id'] =  [
                'value' => $user['id'],
                'label' =>  $user['name'],
            ];

            $arrCollection = [];
            foreach( $customers as $customer){
                $customerProfile = CustomerCreationProfile::find($customer['customer_id']);

                $arrCollection[] = [
                    'value' => $customerProfile['id'],
                    'label' =>  $customerProfile['customer_name'],
                ];
            }

            $calltobdm['customer_id'] =  $arrCollection;

            return response()->json([
                'status' => 200,
                'calltobdm' => $calltobdm,
            ]);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\calltobdm  $calltobdm
     * @return \Illuminate\Http\Response
     */
    public function edit(calltobdm $calltobdm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\calltobdm  $calltobdm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if($userid){
            $calltobdm               = calltobdm::find($id);
            $calltobdm->user_id      = $request->staffName;
            // $calltobdm->created_userid  =   $userid;
            $calltobdm->edited_userid   = $userid;
            $calltobdm->save();
        } 

        if ($calltobdm) {

            $delete = calltobdm_has_customer::where('calltobdm_id', $calltobdm["id"])->delete();

            foreach($request->customer as $customer){

                $cus = new calltobdm_has_customer;
                $cus->calltobdm_id =  $calltobdm['id'];
                $cus->customer_id =  $customer['value'];
                $cus->save();

            }


            return response()->json([
                'status'    => 200,
                'message'   => 'New Call to BDM Updated Successfully',
            ]);
        }else{
            return response()->json([
                'status'    => 400,
                'message'   => 'Unable to save!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\calltobdm  $calltobdm
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            $calltobdm = calltobdm::destroy($id);
            if($calltobdm)    
            {return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!"
            ]);}
            else
            {return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.',
                "errormessage" => "",
            ]);}
        }catch(\Illuminate\Database\QueryException $ex){
            $error = $ex->getMessage(); 
            
            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete! This data is used in another file/form/table.',
                "errormessage" => $error,
            ]);
        }
    }
}

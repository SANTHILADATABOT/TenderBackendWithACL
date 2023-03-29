<?php

namespace App\Http\Controllers;

use App\Models\CustomerCreationProfile;
use App\Models\CustomerCreationMain;
use App\Models\StateMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;

class CustomerCreationProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $customercreationList = DB::table('customer_creation_profiles')
        ->join('country_masters','country_masters.id','customer_creation_profiles.country')
        ->join('state_masters','state_masters.id','customer_creation_profiles.state')
        ->join('district_masters','district_masters.id','customer_creation_profiles.district')
        ->join('city_masters','city_masters.id','customer_creation_profiles.city')
        ->where([
            'customer_creation_profiles.delete_status'=>0,
        ])
        ->select(
            'customer_creation_profiles.id',
            'customer_creation_profiles.customer_name',
            'country_masters.country_name',
            'state_masters.state_name',
            'city_masters.city_name',
            'customer_creation_profiles.smart_city'
        )
        ->get();

        return response()->json([
            'customercreationList' =>   $customercreationList
        ]);
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
        //get the user id
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];
        // $CustomerCreation = CustomerCreationProfile::firstOrCreate($request->profileData);
        if($userid){

            $CustomerCreation = new CustomerCreationProfile;
            // $CustomerCreation -> customer_no = $request->profileData['customer_no'] ;
            $customerno = $this->getCustNo1($request->profileData['state'], $request->profileData['smart_city']);
            $CustomerCreation -> customer_no =    $customerno;
            $CustomerCreation -> customer_category = $request->profileData['customer_category'] ;
            $CustomerCreation -> customer_name = $request->profileData['customer_name'] ;
            $CustomerCreation -> smart_city = $request->profileData['smart_city'] ;
            $CustomerCreation -> customer_sub_category = $request->profileData['customer_sub_category'] ;
            $CustomerCreation -> country = $request->profileData['country'] ;
            $CustomerCreation -> state = $request->profileData['state'] ;
            $CustomerCreation -> district = $request->profileData['district'] ;
            $CustomerCreation -> city = $request->profileData['city'] ;
            $CustomerCreation -> pincode = $request->profileData['pincode'] ;
            $CustomerCreation -> address = $request->profileData['address'] ;
            $CustomerCreation -> phone = $request->profileData['phone'] ;
            $CustomerCreation -> pan = $request->profileData['pan'] ;
            $CustomerCreation -> mobile_no = $request->profileData['mobile_no'] ;
            $CustomerCreation -> email = $request->profileData['email'] ;
            $CustomerCreation -> gst_registered = $request->profileData['gst_registered'] ;
            $CustomerCreation -> gst_no = $request->profileData['gst_no'] ;
            $CustomerCreation -> website = $request->profileData['website'] ;
            $CustomerCreation -> createdby_userid = $userid ;
            $CustomerCreation -> updatedby_userid = 0 ;
            $CustomerCreation -> save();
        }

        if ($CustomerCreation) {
            return response()->json([
                'status' => 200,
                'message' => 'Customer Profile Has created Succssfully!',
                'id' => $CustomerCreation['id'],
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
     * @param  \App\Models\CustomerCreationProfile  $customerCreationProfile
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $CustomerCreation = CustomerCreationProfile::find($id);
        if ($CustomerCreation)
            return response()->json([
                'status' => 200,
                'profiledata' => $CustomerCreation
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
     * @param  \App\Models\CustomerCreationProfile  $customerCreationProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCreationProfile $customerCreationProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerCreationProfile  $customerCreationProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id )
    {
        //
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if($userid){
            $updatedata = $request->profileData;
            $updatedata['updatedby_userid']= $userid;

            $savedData = CustomerCreationProfile::find($id);
            if($savedData['state'] !=  $updatedata['state']){
                $updatedata['customer_no']  = $this->getCustNo1( $updatedata['state'], $updatedata['smart_city']);
            }

            $CustomerCreation = CustomerCreationProfile::findOrFail($id)->update($updatedata);
        }


        if ($CustomerCreation)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!"
            ]);
        else {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerCreationProfile  $customerCreationProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        // $deleteCustomer = CustomerCreationProfile::where('id',$id)->update([
        //     'delete_status' => 1,
        // ]);
        // if ($deleteCustomer)
        //     return response()->json([
        //         'status' => 200,
        //         'message' => "Deleted Successfully!"
        //     ]);

        try{
            $deleteCustomer = CustomerCreationProfile::destroy($id);
            if($deleteCustomer)
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

    public function getProfileFromData(Request $request){

        $user = Token::where('tokenid', $request->tokenid)->first();
        $profileFormData = null;
        if($user){
            $userid = $user['userid'];

            $userid = $user['userid'];
            $customercreation = CustomerCreationMain::where([
            ['user_id', $userid ],
            ['isCustCreationProcessCompleted',0],
            ['delete_status',0]
           ])->orderBy('id', 'desc')->first();

            $profileFormData = CustomerCreationProfile::where([
            ['userid', $userid ],
            ['cust_creation_mainid', $customercreation['id']],
            // ['isProfileFormCompleted',1],
            // ['isCustCreationCompleted',0],
            ['delete_status',0]
           ])->orderBy('id', 'desc')->first();
        }

        return response()->json([
            'profileFormData' =>  $profileFormData,
            'customercreation' => $customercreation
        ]);
    }


    public function getFormNo(){
        $profileFormData = CustomerCreationProfile::where([
            ['isProfileFormCompleted',1],
            ['delete_status',0]
        ])->orderBy('id', 'desc')->first();

        if($profileFormData){
            $form_no = $profileFormData['form_no'];
        }else{
            $form_no = 0;
        }

        return response()->json([
            'form_no' =>   ($form_no + 1)
        ]);
    }

    public function getCustNo($stateid){
        $no =  $this->generateNo($stateid);
        return response()->json([
            'no'  => $no ,
        ]);
    }

    public function generateNo($stateid){
        $custno = CustomerCreationProfile::where([
            ['state', $stateid],
            ['delete_status',0]
        ])->orderBy('id', 'desc')->first();

        if($custno){
            $lastNo = $custno['customer_no'];
            $lastNoarr = explode("-",$lastNo);
            $no = $lastNoarr[2] + 1;
        }else{
            $no = 1;
        }
        return sprintf('%02d', $no);
    }

    public function getCustNo1($stateid, $smartcity){
        $no = $this->generateNo($stateid);
        $state = StateMaster::where('id', $stateid)->get();
        if ($state){
           $statecode= $state[0]['state_code'];

           if($smartcity=="yes") { $SC = "SC";}
           if($smartcity=="no") { $SC = "NC";}

           return strtoupper($statecode."-".$SC."-".$no);
        //    return ( $state );

        }

    }

    public function getUlbs($savedulb){
        $ulbs = CustomerCreationProfile::orderBy('id', 'desc')
        ->get();

        $ulbList= [];
        foreach($ulbs as $ulb){
            $ulbList[] = ["value" => $ulb['id'], "label" =>  $ulb['customer_name']] ;
        }
        return  response()->json([
            'ulbList' =>  $ulbList,

        ]);
    }


    public function getList(){

        $countrys = CustomerCreationProfile::where("customer_name", "!=", "")->get();

        $customerList= [];
        foreach($countrys as $country){
            $customerList[] = ["value" => $country['id'], "label" =>  $country['customer_name']] ;
        }
        return  response()->json([
            'customerList' =>  $customerList,

        ]);
    }

    public function getOptions(){

        $customers = CustomerCreationProfile::where("customer_name", "!=", "")->get();

        $customerList= [];
        foreach($customers as $customer){
            $customerList[] = ['value' => $customer['id'], 'label' => $customer['customer_name']];
        }

        return  response()->json([
            'customerList' =>  $customerList,
        ]);
    }
}

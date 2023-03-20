<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Token;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserTypeController extends Controller
{
    //
    public function index()
    {
        //
        $userType = Role::orderBy('id', 'asc')->get();
      
    
        if ($userType)
            return response()->json([
                'status' => 200,
                'userType' => $userType
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function getoptions()
    {
        //
        $userType = Role::where('activeStatus', 'active')->orderBy('id', 'asc')->get();
      
    
        if ($userType)
            return response()->json([
                'status' => 200,
                'userType' => $userType
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
        ]);
    
        $user = Token::where('tokenid', $request->tokenId)->first();   
        // return  $user;
        $userid = $user['userid'];

        if($user){
            $role = Role::create([
                'name' => $request->input('name'),
                'activeStatus' =>  $request->input('activeStatus')
            ]); 
        }
        
        if($role){
            return response()->json([
                'status'    => 200,
                'message'   => $request->input('name').' Role Created Successfully',
                'id'        => $role['id']
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Unable to Create!'
            ]);
        }


    }

    public function update(Request $request,  $id)
    {
        //
        $userType = Role::where('name', '=', $request->name)
        ->where('id', '!=', $id)->exists();
        if ($userType) {
            return response()->json([
                'status' => 400,
                'errors' => 'User Type Already Exists'
            ]);
        }
        
        // $validator = Validator::make($request->all(), ['name' => 'required|unique:roles,name']);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 400,
        //         'errors' => $validator->messages(),
        //     ]);
        // }

        $userType = Role::findOrFail($id)->update($request->all());
        if ($userType)
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

    public function show($id)
    {
        //
        $usertype = Role::find($id);
        if ($usertype)
            return response()->json([
                'status' => 200,
                'usertype' => $usertype
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
        //
        try{

            $userType = Role::destroy($id);
            if($userType)    
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

<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;

use App\Models\Token;
use Illuminate\Support\Facades\Auth;
use Symfony\Contracts\Service\Attribute\Required;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class UserControllerTemp extends Controller
{
    function login1(Request $req)
    {
        
         
        $milliseconds = floor(microtime(true) * 1000);
        
        $tokenId = bin2hex(random_bytes(16)).$milliseconds;
        
        $credentials = $req->only('name', 'password');


        //return "test_".$credentials;
         
        
        // $user = UserCreation::where([['user_name', $req->user_id], ['password', $req->password]])->first();
        // $user = User::where([['name', $req->user_id], ['password', $req->password]])->first();
        if (!Auth::attempt($credentials)) {
            return ([
                "msg" => "Invlaid User Name or Password",
                "logStatus" => "error"
            ]);
        }
       
            $user = Auth::user();
            
            $token = new Token;
            $token -> tokenId = $tokenId;
            $token -> userid = $user['id'];
            $token -> isLoggedIn = 1;
            $token->save();

            $user = auth()->user();
            $roles= $user->roles->pluck('name');
            $permission= $user->getPermissionsViaRoles()->pluck('name');

            // $user['tokenId'] = $tokenId;
            
            // $user_resource = new UserResource(auth()->user());

            // $user_resource['tokenId'] = 
            return ([
                "msg" => "Login Successfully",
                "logStatus" => "success",
                "tokenId" => $tokenId,
                'role'=>$roles,
                'permission'=>$permission
                // 'userdetails'=> $user
            ]);
       
    }

    function logout(Request $req){

        $logout = Token::where('tokenid', $req->tokenid )
        ->update([
           'isLoggedIn' => 0
        ]);

        if ($logout)
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

    function validateToken(Request $request){

        //get the user id 
        $user = Token::where('tokenid','=' ,$request->tokenid)->first();   
        
        if($user){
            return response()->json([
                'isValid' => true
            ]);
        }else{
            return response()->json([
                'isValid' => false
            ]);
        }
    }

    public function index()
    {

        $userlist = DB::table('users')->select('*')
        ->get();

        return response()->json([
            "attendanceTypelist" => $userlist
        ]);


       
    }

    public function store(Request $request){
       
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if($userid)
        {

            $validate = $request->validate([
                'name' => ['required'],
                'user_role' =>["required"],
                'login_id' =>["required"],
                'password' =>['required'],
                'phone' => ['required','unique:users','regex:/[0-9]{10}/'],
                'photo' => ['required']

            ]);

            if($request ->hasFile('photo'))
            {
                $file = $request->file('photo');
                $filename_original = $file->getClientOriginalName();
                $fileName =intval(microtime(true) * 1000) . $filename_original;
                $file->storeAs('UserProfile/userphotos', $fileName, 'public');
                $mimeType =  $file->getMimeType();
                $filesize = ($file->getSize())/1000;
                $ext =  $file->extension();
            }

           


            $userCreation = new User;
            $userCreation->name = $request->name;
            $userCreation->user_role = $request->user_role;
            $userCreation->email = $request->email;
            $userCreation->password = $request->password;
            $userCreation->phone = $request->phone;
            $userCreation->photo =$fileName;
            $userCreation->createdby = $userid;
            $userCreation->save();

        }
        if($userCreation){
            return response()->json([
                "status" => 200,
                "data" => $userCreation->id
            ]);
        }else{

            return response()->json([
                "status"=>400,
                "message"=>"Unable to Save !"

            ]);
         }

       

    }

    public function show($id)
    {
        
        $userCreation = User::find($id);
        if ($userCreation)
        {
            return response()->json([
                'status' => 200,
                'AttendanceTypeData' => $userCreation
            ]);
        }
            
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are Invalid'
            ]);
        }
    }

    public function updatedfile(Request $request, $id){
      
      
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        try
        {


            if($userid)
            {
               
                
                $validate = $request->validate([
                    'name' => ['required'],
                    'user_role' =>["required"],
                    'login_id' =>["required"],
                    'password' =>['required'],
                    'phone' => ['required','regex:/[0-9]{10}/'],
                     'photo' => ['required']
    
                ]);
               
               
               
                
                if($request ->hasFile('photo'))
            {
               
            $document = User::find($id);
            $filename = $document['photo'];
            $file_path = public_path()."/uploads/UserProfile/userphotos/".$filename;
            
             
            if(File::exists($file_path)) { 
                // return "path_".$file_path;
                
                if(File::delete($file_path)){

                    $file = $request->file('photo');
                $filename_original = $file->getClientOriginalName();
                $fileName =intval(microtime(true) * 1000) . $filename_original;
                $file->storeAs('UserProfile/userphotos', $fileName, 'public');
                $mimeType =  $file->getMimeType();
                $filesize = ($file->getSize())/1000;
                $ext =  $file->extension();

                    }
                   
                }
               
                
            }
           
            
           
                $userCreation = User::findOrFail($id);

                

                        $userCreation->name = $request->name;
                        $userCreation->user_role = $request->user_role;
                        $userCreation->email = $request->email;
                        $userCreation->password = $request->password;
                        $userCreation->phone = $request->phone;
                        $userCreation->photo = $fileName;
                        $userCreation->updatedby = $userid;
                        $userCreation->save();

                    if($userCreation){

                        return response()->json([
                            "status"=>200,
                            "message"=> "Updated Successfully!"
                        ]);


                    }else{

                        return response()->json([
                            "status"=>400,
                            "message"=> "Unable to Update!"
                        ]);


                    }

                    
                


            }
        
         }

            


         catch(\Illuminate\Database\QueryException $ex){

            $errors = $ex->getMessage();

            return response()->json([
                "satatus" => 404,
                "message" => $errors
            ]);

         }

    }


    public function destroy($id)
    {

        try{
            $deleteuserCreation = User::destroy($id);
            if($deleteuserCreation)
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
        }
        catch(\Illuminate\Database\QueryException $ex){
            $error = $ex->getMessage();

            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete! This data is used in another file/form/table.',
                "errormessage" => $error
            ]);
        }
    }

}

 
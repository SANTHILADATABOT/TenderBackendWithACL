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
use Spatie\Permission\Models\Role;

// use Illuminate\Support\Facades\Hash;


class UserControllerTemp extends Controller
{
    function login1(Request $req)
    {


        $milliseconds = floor(microtime(true) * 1000);

        $tokenId = bin2hex(random_bytes(16)) . $milliseconds;

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
        $token->tokenId = $tokenId;
        $token->userid = $user['id'];
        $token->isLoggedIn = 1;
        $token->save();

        $user = auth()->user();
        $roles = $user->roles->pluck('name');
        $permission = $user->getPermissionsViaRoles()->pluck('name');

        // $user['tokenId'] = $tokenId;

        // $user_resource = new UserResource(auth()->user());

        // $user_resource['tokenId'] = 
        return ([
            "msg" => "Login Successfully",
            "logStatus" => "success",
            "tokenId" => $tokenId,
            'role' => $roles,
            'permission' => $permission
            // 'userdetails'=> $user
        ]);
    }

    function logout(Request $req)
    {

        $logout = Token::where('tokenid', $req->tokenid)
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

    function validateToken(Request $request)
    {

        //get the user id 
        $user = Token::where('tokenid', '=', $request->tokenid)->first();

        if ($user) {
            return response()->json([
                'isValid' => true
            ]);
        } else {
            return response()->json([
                'isValid' => false
            ]);
        }
    }

    public function index()
    {
        $userlist = DB::table('users as u')->select('u.*','m.role_id','r.name as role_name')
        ->join('model_has_roles as m','m.model_id','u.id')
        ->join('roles as r','r.id','m.role_id')
        ->get();
        
        return response()->json([
            "userlist" => $userlist
        ]);
    }

    public function store(Request $request)
    {
        //name in DB               --    Name in API payload
        //name                     --    loginId
        //userName                 --   userName
        //userType                 --   userType
        //password                 --   password
        //confirmPassword                 --   confirmPassword
        //filename(hased & stored here)                 --   file 
        //email                 --   email

        $user = Token::where('tokenid', $request->tokenId)->first();
        $userid = $user['userid'];

        if ($userid) {
            $validate = $request->validate([
                'userName' => ['required'],
                'userType' => ["required"],
                'loginId' => ["required"],
                'password' => ['required'],
                'confirmPassword' => ['required'],
                'mobile' => ['required', 'unique:users', 'regex:/[0-9]{10}/'],
                'file' => ['required'],
                'email' => ['required'],
            ]);
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename_original = $file->getClientOriginalName();
                $fileName = intval(microtime(true) * 1000) . $filename_original;
                $file->storeAs('UserProfile/userphotos', $fileName, 'public');
                // $mimeType =  $file->getMimeType();
                $filesize = ($file->getSize()) / 1000;
                $ext =  $file->extension();
            }
            
            $userCreation = new User;
            $userCreation->name = $request->loginId;  // to be a login id
            $userCreation->userType = $request->userType;
            $userCreation->userName = $request->userName;
            $userCreation->email = $request->email;
            $userCreation->password = bcrypt($request->password);
            $userCreation->confirm_passsword = $request->password;
            $userCreation->mobile = $request->mobile;
            $userCreation->filename = $fileName;
            $userCreation->original_filename = $filename_original;
            $userCreation->filesize = $filesize;
            $userCreation->fileext = $ext;
            $userCreation->createdby = $userid;
            $userCreation->save();
        }
        if ($userCreation) {

            $role = Role::find($request->userType);
            $userCreation->assignRole($role);

            return response()->json([
                "status" => 200,
                "data" => $userCreation->id
            ]);
        } else {

            return response()->json([
                "status" => 400,
                "message" => "Unable to Save !"

            ]);
        }
    }

    public function show($id)
    {

        $userCreation = User::find($id);
        if ($userCreation) {
            return response()->json([
                'status' => 200,
                'user' => $userCreation
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are Invalid'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];
        try {
            if ($userid) {
                // $validate = $request->validate([
                //     'name' => ['required'],
                //     'user_role' => ["required"],
                //     'login_id' => ["required"],
                //     'password' => ['required'],
                //     'phone' => ['required', 'regex:/[0-9]{10}/'],
                //     'photo' => ['required']

                // ]);

                $validate = $request->validate([
                    'userName' => ['required'],
                    'userType' => ["required"],
                    'loginId' => ["required"],
                    'password' => ['required'],
                    'confirmPassword' => ['required'],
                    'mobile' => ['required', 'unique:users', 'regex:/[0-9]{10}/'],
                    'file' => ['required'],
                    'email' => ['required'],
                ]);


                if ($request->hasFile('file')) {

                    $document = User::find($id);
                    $filename = $document['file'];
                    $file_path = public_path() . "/uploads/UserProfile/userphotos/" . $filename;

                    if (File::exists($file_path)) {

                        if (File::delete($file_path)) {
                            $file = $request->file('file');
                            $filename_original = $file->getClientOriginalName();
                            $fileName = intval(microtime(true) * 1000) . $filename_original;
                            $file->storeAs('UserProfile/userphotos', $fileName, 'public');
                            $mimeType =  $file->getMimeType();
                            $filesize = ($file->getSize()) / 1000;
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

                if ($userCreation) {

                    return response()->json([
                        "status" => 200,
                        "message" => "Updated Successfully!"
                    ]);
                } else {

                    return response()->json([
                        "status" => 400,
                        "message" => "Unable to Update!"
                    ]);
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {

            $errors = $ex->getMessage();

            return response()->json([
                "satatus" => 404,
                "message" => $errors
            ]);
        }
    }


    public function destroy($id)
    {

        try {
            $deleteuserCreation = User::destroy($id);
            if ($deleteuserCreation) {
                return response()->json([
                    'status' => 200,
                    'message' => "Deleted Successfully!"
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect.',
                    "errormessage" => "",
                ]);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();

            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete! This data is used in another file/form/table.',
                "errormessage" => $error
            ]);
        }
    }


    function getRolesAndPermissions(Request $request)
    {
        $token = Token::where('tokenid', '=', $request->tokenid)->first();

        $userid = $token->userid;

        $user = User::find($userid);

        if ($user) {
            $roles = $user->roles->pluck('name');
            $permission = $user->getPermissionsViaRoles()->pluck('name');
            return response()->json([
                'role' => $roles,
                'permission' => $permission
            ]);
        } else {
            return response()->json([
                'isValid' => false
            ], 401);
        }
    }
}

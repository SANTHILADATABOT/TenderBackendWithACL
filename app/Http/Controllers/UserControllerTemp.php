<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCreation;
use App\Models\Token;

class UserControllerTemp extends Controller
{
    function login1(Request $req)
    {
        $milliseconds = floor(microtime(true) * 1000);
        $tokenId = bin2hex(random_bytes(16)).$milliseconds;


        $user = UserCreation::where([['user_name', $req->user_id], ['password', $req->password]])->first();
        if ($user) {
            
            $token = new Token;
            $token -> tokenId = $tokenId;
            $token -> userid = $user['user_id'];
            $token -> isLoggedIn = 1;
            $token->save();

            return ([
                "msg" => "Login Successfully",
                "logStatus" => "success",
                "tokenId" => $tokenId,
                // 'userdetails'=> $user
            ]);
        } else {
            return ([
                "msg" => "Invlaid User Name or Password",
                "logStatus" => "error"
            ]);
        }
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
}
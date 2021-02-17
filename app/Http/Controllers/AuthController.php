<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
  
    public function checkVerificationResponse($user) {
        $user_id = $user->id;
        $token = encrypt('moph$'.$user_id);
        $token->save();
        if (!$user->email_verified_at) {
          return response()->json([
            'message' => 'VERIFY_EMAIL',
            'user_id' => $user->id,
            'email' => $user->email,
            // 'token' => $user->api_token,
          ], 403);
        }
    
        return response()->json([
          'user_id' => $user->id,
          'token_type' => 'Bearer',
          'token' => $token,
        ]);
      }
    public function signup(Request $request)
      {
          $request->validate([
              'name' => 'required|string',
              'email' => 'required|string|email|unique:users',
              'password' => 'required|string|confirmed'
          ]);
          $user = new User([
              'name' => $request->name,
              'email' => $request->email,
              'password' => bcrypt($request->password)
              
          ]);
  
  
          $user->save();
          return response()->json([
              'message' => 'Successfully created user!'
          ], 201);
      }

    
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only(['email', 'password']);
        //if(Auth::attempt($credentials)) {
            //return $this->CheckVerificationResponse(Auth::user());

        //}
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'message' => 'Successfully logged in',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
            ], 200);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Mail;
use DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login','resetPassword']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        if (filter_var(request('email'), FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
            $credentials = request([$field, 'password']);
            $user=User::where( $field,request('email'))->first();
        } else {
            $field = 'username';
            $credentials = request([$field, 'password']);
            $credentials = array_merge($credentials, ['username' => strtoupper(request('email'))]);
            $user=User::where( $field,request('username'))->first();
        }
      // var_dump($field);
        // $credentials = request([$field, 'password']);
        //var_dump($credentials);
        if (!$token = Auth::attempt($credentials)) {
            //has failed one time (username is like email)
            $field = 'username';
            $credentials = request([$field, 'password']);
            $credentials = array_merge($credentials, ['username' => strtoupper(request('email'))]);

            if (!$token = Auth::attempt($credentials)){
                return response()->json(['error' => 'Unauthorized'], 401);
            }else{
                return $this->respondWithToken($token,$user); // pass as a username
            }

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token,$user);// pass as email
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
       Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token,$user=null)
    {
        return response()->json([
            'access_token' => $user->createToken('api token of '.$user->name)->plainTextToken,
            'token_type' => 'bearer'
        ]);
    }


    public function random_strings($length_of_string)
    {
        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        // Shuffle the $str_result and returns substring
        // of specified length
        return substr(
            str_shuffle($str_result),
            0,
            $length_of_string
        );
    }


    public function resetPassword(Request $request)
    {
        $emailOrUsername=$request['email'];
        $user=DB::table('users')->where('email','=',$emailOrUsername)->first();
        if($user==null){// email fails
            $user = DB::table('users')->where('username', '=', $emailOrUsername)->first();
            if($user==null){// username fails
                return response()->json(['error' => 'not found email or username'], 301);
            }
        }

       // return (array)$user;

        $random=$this->random_strings(8);

       $hashPass= Hash::make($random);
        DB::table('users')->where('id','=',(array)$user->id)->update(['password'=> $hashPass]);

        Mail::send(['html' => 'template-parts.notes'],['notes' => 'Your new code is: '.$random],function ($message) use ($user) {
            $message->to((array)$user->email, config('app.name'))->subject('reset password');
            $message->from(config('ea.primary_mail'), config('app.name'));
        });

        return  response()->json('Η αποστολή ολοκληρώθηκε', 200);

    }


}

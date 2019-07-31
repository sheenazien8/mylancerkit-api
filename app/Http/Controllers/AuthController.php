<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\JWTAuth;


class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function login(Request $request)
    {

        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);
        try {
            if (! $token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => '',
                    'message' => 'User Not Found',
                    'error' => true
                ], 422);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json([
                'token_expired'
            ], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json([
                'token_invalid'
            ], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json([
                'token_absent' => $e->getMessage()
            ], 500);

        }

        $user = app('auth')->user();
        $user->load('profile');

        if (!$user->status) {
            return response()->json([
                'status' => '',
                'message' => 'User Is Inactive',
                'error' => true
            ], 422);
        }

        return response()->json(compact('token', 'user'));
    }

    public function register(Request $request)
    {
        $validation = $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            're_password' => 'required|same:password',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = app('hash')->make($request->password);
        $user->verification_code =$this->generateRandomString();
        // $user->save();

        $profile = new Profile();
        $profile->user()->associate($user);
        // $profile->save();

        Mail::to($user->email)->send(new WelcomeMail($user));

        return response()->json([
            'message'=> 'Success create your account for the verification please check your email',
            'success' => true
        ], 200);
    }

    public function logout() {
        app('auth')->guard('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'logout'
        ], 200);
    }

    public function activatingAccount($user, $verification_code)
    {
        $user = User::find($user);

        if (!$user) {
            return response()->json([
                'status' => 'fail',
                'message' => 'User Not Found',
                'error' => true
            ], 422);
        }
        if ($user->verification_code == $verification_code) {
            $user->status = true;
            $user->email_verified_at = Carbon::now()->format('Y-m-d h:i:s');
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => $user->email . ' has been activated!!'
            ], 200);
        }else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Verification token is note same with anything user!!',
                'error' => true
            ], 422);
        }
    }
    private function generateRandomString($length = 120)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

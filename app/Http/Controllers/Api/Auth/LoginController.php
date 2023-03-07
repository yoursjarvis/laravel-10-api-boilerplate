<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $validated_request = $request->validated();

        $user = User::query()->where('username', $validated_request['username'])->first();

        if (!$user) {
            $response['messages'] = 'User does not exist.';
            return response()->json(['data' => $response], 404);
        }

        $login_credentials = [
            'username'     => $validated_request['username'],
            'password'  => $validated_request['password']
        ];

        if (Auth::attempt($login_credentials)) {
            $auth = Auth::user();
            $token = $auth->createToken('LaravelSanctumAuth')->plainTextToken;
            $data = [
                'last_login_at' => Carbon::now(),
                'last_login_ip' => $request->ip(),
            ];

            $updated_user = tap($user)->update($data);

            $response['message'] = 'Login SuccessFull.';
            $response['token'] = $token;
            $response['name'] =  $auth->full_name;
            $response['user'] = $updated_user;

            return response()->json(['data' => $response], 200);
        }

        $response['messages'] = 'Password Mismatch.';
        return response()->json(['data' => $response], 401);
    }
}

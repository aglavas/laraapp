<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Resources\Auth\AuthResource;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * Authenticates user using credentials and returns token
     *
     * @param AuthLoginRequest $request
     * @param AuthManager $authManager
     * @return AuthController|\Illuminate\Http\JsonResponse
     */
    public function login(AuthLoginRequest $request, AuthManager $authManager)
    {
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = $authManager->user();
            $userResource = AuthResource::make($user);

            return $this->successDataResponse($userResource, 200);
        }

        return $this->errorMessageResponse('Wrong email/password combination.', 401);
    }

    /**
     * Invalidate token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $token = Auth::user()->token();
        $token->revoke();

        return $this->successMessageResponse('Token invalidated', 200);
    }
}

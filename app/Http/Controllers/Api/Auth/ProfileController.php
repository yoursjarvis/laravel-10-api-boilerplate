<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ChangePasswordRequest;
use App\Http\Requests\Api\Users\UpdateAdminUserRequest;
use App\Services\Api\Auth\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(public ProfileService $profileService)
    {
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        return $this->profileService->changePassword($request);
    }

    public function profile(): JsonResponse
    {
        return $this->profileService->profile();
    }

    public function update(UpdateAdminUserRequest $adminUserRequest): JsonResponse
    {
        return $this->profileService->update($adminUserRequest);
    }
}

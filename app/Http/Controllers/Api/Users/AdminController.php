<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\StoreAdminRequest;
use App\Http\Requests\Api\Users\UpdateAdminRequest;
use App\Services\Api\Users\AdminService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(public AdminService $adminService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->adminService->index($request);
    }

    public function restore($id): JsonResponse
    {
        return $this->adminService->restore($id);
    }

    public function show($id): JsonResponse
    {
        return $this->adminService->show($id);
    }

    public function store(StoreAdminRequest $request): JsonResponse
    {
        return $this->adminService->store($request);
    }

    public function trashed($id): JsonResponse
    {
        return $this->adminService->trashed($id);
    }

    public function update(UpdateAdminRequest $request, $id): JsonResponse
    {
        return $this->adminService->update($request, $id);
    }
}

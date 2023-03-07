<?php

namespace App\Services\Api\Users;

use App\Http\Actions\FiltersQuery;
use App\Http\Requests\Api\Users\StoreAdminRequest;
use App\Http\Requests\Api\Users\UpdateAdminRequest;
use App\Mail\SendWelcomeToAdmin;
use App\Models\Admin;
use App\Services\BaseService;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminService extends BaseService
{
    public function __construct(public UserService $userService)
    {
    }

    public function index($request): JsonResponse
    {
        $data = Admin::sorted($request->sorted_by);

        if (isset($request->q)) {
            $data = Admin::search($data, $request->q);
        }

        if ($request->filter) {
            $data = FiltersQuery::filter($data, $request);
        }

        return $this->handleResponse($data->get(), '', 200);
    }

    public function restore($id)
    {
        if (!$admin = Admin::onlyTrashed()->find($id)) {
            return $this->handleError([], 'Admin Not Found on Trashed.', 404);
        }

        $updated_by = Auth::id();
        $admin->restore();

        $data = [
            'deleted_by' => null,
            'is_active'  => 1,
            'updated_by' => $updated_by
        ];

        $restored_admin = tap($admin)->update($data);

        return $this->handleResponse($restored_admin, 'Admin Restored Successfully.', 200);
    }

    public function show($id): JsonResponse
    {
        $admin = Admin::query();

        if (!$admin->withTrashed()->find($id)) {
            return $this->handleError([], 'Admin Not Found.', 404);
        }

        $data = $admin->with('createdBy', 'deletedBy', 'updatedBy')->first();

        return $this->handleResponse($data, '', 200);
    }

    public function store(StoreAdminRequest $request): JsonResponse
    {
        try {
            $admin = DB::transaction(function () use ($request) {
                if (Auth::user()) {
                    $created_by = Auth::user()->profile->id;
                } else {
                    $created_by = null;
                }

                $validated_request = $request->validated();

                $password = $request['password'];

                $admin = Admin::create([
                    'first_name'                     => $validated_request['first_name'],
                    'middle_name'                    => isset($validated_request['middle_name']) ? $validated_request['middle_name'] : null,
                    'last_name'                      => $validated_request['last_name'],
                    'username'                       => $validated_request['username'],
                    'personal_email'                 => $validated_request['personal_email'],
                    'company_email'                  => $validated_request['company_email'],
                    'country_calling_code'           => $validated_request['country_calling_code'],
                    'mobile_number'                  => $validated_request['mobile_number'],
                    'alternate_country_calling_code' => isset($validated_request['alternate_country_calling_code']) ? $validated_request['alternate_country_calling_code'] : null,
                    'alternate_mobile_number'        => isset($validated_request['alternate_mobile_number']) ? $validated_request['alternate_mobile_number'] : null,
                    'gender'                         => isset($validated_request['gender']) ? $validated_request['gender'] : null,
                    'date_of_birth'                  => isset($validated_request['date_of_birth']) ? $validated_request['date_of_birth'] : null,
                    'avatar'                         => isset($validated_request['avatar']) ? $validated_request['avatar'] : null,
                    'is_active'                      => $validated_request['is_active'],
                    'created_by'                     => $created_by,
                    'updated_by'                     => $created_by,
                ]);

                $this->userService->store($password, $admin);

                Mail::to($validated_request['personal_email'])->send((new SendWelcomeToAdmin($admin, $password))->afterCommit());

                return $admin;
            });
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }

        $msg = 'Admin Created Successfully.';
        return $this->handleResponse($admin, $msg, 201);
    }

    public function trashed($id): JsonResponse
    {
        if (!$admin = Admin::withTrashed()->find($id)) {
            return $this->handleError([], 'Admin Not Found.', 404);
        }

        if ($admin->onlyTrashed()->find($id)) {
            return $this->handleError([], 'Selected Admin already in Trashed.', 404);
        }

        $deleted_by = Auth::id();
        $admin->delete();

        $data = [
            'deleted_by' => $deleted_by,
            'is_active'  => 0
        ];

        $trashed_admin = tap($admin)->update($data);

        return $this->handleResponse($trashed_admin, 'Admin Trashed Successfully.', 200);
    }

    public function update(UpdateAdminRequest $request, $id): JsonResponse
    {
        if (!$admin = Admin::withTrashed()->find($id)) {
        return $this->handleError([], 'Admin Not Found.', 404);
    }

        $validated_request = $request->validated();

        try {
            $updated_admin = DB::transaction(function () use ($admin, $validated_request) {
                $updated_by = Auth::user()->profile->id;

                $data = [
                    'first_name'                     => $validated_request['first_name'],
                    'middle_name'                    => isset($validated_request['middle_name']) ? $validated_request['middle_name'] : null,
                    'last_name'                      => $validated_request['last_name'],
                    'username'                       => $validated_request['username'],
                    'personal_email'                 => $validated_request['personal_email'],
                    'company_email'                  => isset($validated_request['company_email']) ? $validated_request['company_email'] : null,
                    'country_calling_code'           => $validated_request['country_calling_code'],
                    'mobile_number'                  => $validated_request['mobile_number'],
                    'alternate_country_calling_code' => isset($validated_request['alternate_country_calling_code']) ? $validated_request['alternate_country_calling_code'] : null,
                    'alternate_mobile_number'        => isset($validated_request['alternate_mobile_number']) ? $validated_request['alternate_mobile_number'] : null,
                    'gender'                         => isset($validated_request['gender']) ? $validated_request['gender'] : null,
                    'date_of_birth'                  => isset($validated_request['date_of_birth']) ? $validated_request['date_of_birth'] : null,
                    'avatar'                         => isset($validated_request['avatar']) ? $validated_request['avatar'] : null,
                    'is_active'                      => $validated_request['is_active'],
                    'updated_by'                     => $updated_by,
                ];

                $updated_admin = tap($admin)->update($data);

                $admin->user()->update([
                    'first_name'     => $updated_admin->first_name,
                    'middle_name'    => $updated_admin->middle_name,
                    'last_name'      => $updated_admin->last_name,
                    'username'       => $updated_admin->username,
                    'personal_email' => $updated_admin->personal_email,
                    'company_email'  => $updated_admin->company_email,
                    'avatar'         => $updated_admin->avatar,
                    'is_active'      => $updated_admin->is_active,
                ]);

                return $updated_admin;
            });
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }

        return $this->handleResponse($updated_admin, 'Admin Updated Successfully.', 200);
    }
}

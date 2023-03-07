<?php

namespace App\Services\Api\Auth;

use App\Http\Requests\Api\Auth\ChangePasswordRequest;
use App\Http\Requests\Api\Users\UpdateAdminUserRequest;
use App\Mail\SendChangePasswordMailToUser;
use App\Services\BaseService;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ProfileService extends BaseService
{
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $validated_request = $request->validated();

        $user = Auth::user();

        if (!Hash::check($validated_request['current_password'], $user['password'])) {
            $response_data['errors'] = 'Incorrect Current Password.';
            return response()->json(['data' => $response_data], 400);
        }

        $user->update([
            'password' => Hash::make($validated_request['new_password'])
        ]);

        Mail::to($user->personal_email)->send(new SendChangePasswordMailToUser($user, $validated_request['new_password']));
        return $this->handleResponse($user->profile, 'Password Updated Successfully.', 200);
    }

    public function profile(): JsonResponse
    {
        return $this->handleResponse(Auth::user()->profile, '', 200);
    }

    public function update(UpdateAdminUserRequest $adminUserRequest): JsonResponse
    {
        $updated_user = DB::transaction(function () use ($adminUserRequest) {
            try {
                $user = Auth::user();
                $profile_type = $user->profile_type;
                $updated_by = Auth::user()->profile->id;

                switch ($profile_type) {
                    case 'App\Models\Admin':

                        $validated_request = $adminUserRequest->validated();

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

                        $updated_profile =  tap($user->profile)->update($data);

                        // dd($updated_profile);
                        break;
                }

                $updated_user = tap($user)->update([
                    'first_name'     => $updated_profile->first_name,
                    'middle_name'    => $updated_profile->middle_name,
                    'last_name'      => $updated_profile->last_name,
                    'username'       => $updated_profile->username,
                    'personal_email' => $updated_profile->personal_email,
                    'company_email'  => $updated_profile->company_email,
                    'avatar'         => $updated_profile->avatar,
                    'is_active'      => $updated_profile->is_active,
                ]);

                return $updated_user;
            } catch (Exception $exception) {
                return $this->handleException($exception);
            }
        });

        return $this->handleResponse($updated_user->profile, 'Profile Updated Successfully.', 200);
    }
}

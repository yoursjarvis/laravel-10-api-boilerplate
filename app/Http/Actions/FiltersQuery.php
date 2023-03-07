<?php

namespace App\Http\Actions;

class FiltersQuery
{
    public static function filter($data, $request): object | null
    {
        if (isset($request['filter']['is_active'])) {

            if ($request['filter']['is_active'] === 'true') {
                $data->where('is_active', 1);
            } else {
                $data->where(function ($q) {
                    $q->where('is_active', 0);
                    $q->orWhereNull('is_active');
                });
            }

            return $data;
        }

        if ($request && isset($request['filter']['trashed'])) {

            if ($request['filter']['trashed'] === 'include') {
                $data->withTrashed();
            }

            if ($request['filter']['trashed'] === 'only') {
                $data->onlyTrashed();
            }

            return $data;
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Admin extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['full_name'];

    protected $casts = [
        'created_by'    => 'int',
        'deleted_by'    => 'int',
        'updated_by'    => 'int',
        'date_of_birth' => 'date',
    ];

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'username', 'personal_email',
        'company_email',
        'country_calling_code',
        'mobile_number',
        'alternate_country_calling_code',
        'alternate_mobile_number',
        'gender',
        'is_active',
        'avatar',
        'date_of_birth',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    //------------------- Relationships -------------------//
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'profile');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    //------------------- Relationships -------------------//


    //--------------------- Attributes --------------------//
    protected function firstName(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => strtolower($value)
        );
    }

    protected function middleName(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => strtolower($value)
        );
    }

    protected function lastName(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => strtolower($value)
        );
    }

    public function getFullNameAttribute(): string
    {
        if ($this->middle_name) {
            return $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
        }

        return $this->first_name . ' ' . $this->last_name;
    }
    //--------------------- Attributes --------------------//


    //---------------------- Methods ----------------------//
    public static function sorted($direction)
    {
        switch ($direction) {
            case 'newest':
                $data = self::latest();
                break;
            case 'asc':
                $data = self::orderBy('id', 'ASC');
                break;
            default:
                $data = self::orderBy('id', 'DESC');
                break;
        }

        return $data;
    }

    public static function search($admin, $search): object
    {
        $admin->withTrashed()->where(function ($q) use ($search) {
            $q->where('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('username', 'LIKE', '%' . $search . '%')
                ->orWhere(DB::raw('concat(first_name," ",middle_name," ",last_name)'), 'like', '%' . $search . '%')
                ->orWhere(DB::raw('concat(first_name," ",last_name)'), 'like', '%' . $search . '%')
                ->orWhere('company_email', 'LIKE', '%' . $search . '%')
                ->orWhere('personal_email', 'LIKE', '%' . $search . '%')
                ->orWhere('id', '=', $search);
        });

        return $admin;
    }
    //---------------------- Methods ----------------------//
}

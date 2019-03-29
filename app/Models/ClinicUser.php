<?php

namespace App\Models\Auth;

use App\Models\Traits\ClinicAttribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\Traits\Scope\ClinicScope;
use App\Models\Auth\Traits\Method\RoleMethod;
use App\Models\Auth\Traits\Attribute\RoleAttribute;

/**
 * Class Role.
 */
class ClinicUser extends Model
{

    use ClinicAttribute;

    protected $table = 'clinic_user';
    protected $guarded = ['id'];



}

<?php

namespace App\Models\Auth;

use App\Models\Traits\LabAttribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\Traits\Scope\LabScope;
use App\Models\Auth\Traits\Method\RoleMethod;
use App\Models\Auth\Traits\Attribute\RoleAttribute;

class Lab extends Model
{

    protected $table = 'labs';
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
    ];


}

<?php

namespace App\Models\Auth;

use App\Models\Traits\LabAttribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\Traits\Scope\LabScope;
use App\Models\Auth\Traits\Method\RoleMethod;
use App\Models\Auth\Traits\Attribute\RoleAttribute;

class Lab extends Model
{

    use LabAttribute;

    protected $table = 'labs';
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'owner_id'
    ];

    public function specialties()
    {
        return $this->belongsToMany(Specialties::class, 'clinic_specialties', 'clinic_id');
    }
}

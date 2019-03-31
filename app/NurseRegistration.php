<?php

namespace App;

use App\Models\Traits\NurseRegistrationAttribute;
use Illuminate\Database\Eloquent\Model;

class NurseRegistration extends Model
{

    use NurseRegistrationAttribute;

    protected $guarded = ['id'];
    protected $table = 'nurse_registrations';

}

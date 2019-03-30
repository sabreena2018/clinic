<?php

namespace App;

use App\Models\Traits\PrivateDoctorRegistrationAttribute;
use Illuminate\Database\Eloquent\Model;

class PrivateDoctorRegistration extends Model
{
    //

    use PrivateDoctorRegistrationAttribute;



    protected $guarded = ['id'];

    protected $table = 'private_doctor_registrations';

}

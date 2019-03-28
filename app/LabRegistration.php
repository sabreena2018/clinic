<?php

namespace App;

use App\Models\Traits\LabAttribute;
use Illuminate\Database\Eloquent\Model;

class LabRegistration extends Model
{


    use LabAttribute;


    protected $table = 'lab_registrations';
    protected $guarded = ['id'];



}

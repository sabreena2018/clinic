<?php

namespace App;

use App\Models\Traits\ReservationAttribute;
use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
    //
    use ReservationAttribute;

    protected $fillable = [
        'type',
        'status',
        'user_id',
        'preferred_time',
        'appointment',
    ];


    protected $table = 'reservations';
}

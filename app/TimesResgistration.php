<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimesResgistration extends Model
{
    protected $fillable = [
        'reservation_id',
        'time'
    ];


    protected $table = 'times_resgistrations';
}

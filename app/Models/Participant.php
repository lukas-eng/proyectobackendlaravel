<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Participant extends Authenticatable
{
    protected $fillable = ['fullname', 'email', 'phone', 'event_id'];
    public $timestamps = false;

}

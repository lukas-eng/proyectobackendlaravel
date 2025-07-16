<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Venue extends Authenticatable
{
    protected $fillable = ['name', 'location'];
    public $timestamps = false;

}

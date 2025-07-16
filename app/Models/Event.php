<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Event extends Authenticatable
{
    
    protected $fillable = ['name', 'date', 'venue_id'];
    public $timestamps = false;

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
protected $fillable = [
    'name',
    'email',
    'password',
    'tree_theme',
    'vanta_theme'
];}
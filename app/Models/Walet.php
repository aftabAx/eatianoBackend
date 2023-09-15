<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Walet extends Model
{

    use HasFactory;
    protected $table = 'walet';
    protected $fillable = ['amount','user_id'];

}

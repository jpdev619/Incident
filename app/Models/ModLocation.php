<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModLocation extends Model
{
    use HasFactory;
    protected $table = 'mod_location';
    protected $primaryKey = 'loc_id';
}

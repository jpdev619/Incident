<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBasic extends Model
{
    use HasFactory;
    protected $table = 'user_basic';
    protected $primaryKey = 'user_id';

    public function user_emp() {
        return $this->belongsTo(UserEmployment::class,'user_xusern','emp_xuser');
    }

    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmployment extends Model
{
    use HasFactory;
    protected $table = 'user_employment';
    protected $primaryKey = 'emp_id';

    public function userbasic_emp() {
        return $this->belongsTo(UserBasic::class,'emp_xuser','user_xusern');
    }
}

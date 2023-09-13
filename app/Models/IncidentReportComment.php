<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentReportComment extends Model
{
    use HasFactory;
    protected $table = 'incident_report_comment';
    protected $primaryKey = 'comment_id';

    public function userbasic() {
        return $this->belongsTo(UserBasic::class,'comment_user_id','user_xusern');
    }
}

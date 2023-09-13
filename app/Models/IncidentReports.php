<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentReports extends Model
{
    use HasFactory;
    protected $table = 'incident_report';
    protected $primaryKey = 'incident_id';

    public function userbasic() {
        return $this->belongsTo(UserBasic::class,'incident_creator','user_xusern');
    }
    public function notify() {
        return $this->belongsTo(UserBasic::class,'incident_tonotify','user_xusern');
    }
    public function useremp() {
        return $this->belongsTo(UserEmployment::class,'incident_creator','emp_xuser');
    }
    public function irUploads() {
        return $this->hasMany(IncidentReportsAttachments::class,'ir_id','incident_number');
    }
    public function ip() {
        return $this->hasMany(IncidentProjectIP::class,'ip_incident_number','incident_number');
    }
}

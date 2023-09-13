<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IncidentReports;
use App\Models\UserBasic;
use App\Models\UserEmployment;
use App\Models\IncidentReportComment;
use App\Models\SystemIncidentType;
use App\Models\SystemApps;
use App\Models\ModLocation;
use App\Models\InventorySystem;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Exports\IncidentReportsExport;




class IncidentReportsController extends Controller
{
    

    public function irdata()
{
    $ir = DB::table('incident_report')
        ->leftJoin('user_basic', 'user_basic.user_xusern', 'incident_report.incident_creator')
        ->leftJoin('user_employment', 'user_employment.emp_xuser', 'incident_report.incident_creator')
        ->leftjoin('inventory_system', 'inventory_system.project', 'incident_report.incident_affected')
        ->selectRaw('incident_report.incident_number,
            incident_report.incident_title,
            incident_report.incident_type,
            incident_id,
            incident_affected,
            incident_location,
            incident_action,
            incident_startdate,
            incident_enddate,
            Incident_creator,
            server_ip as serverip,
            CONCAT(user_xfirstname, " ", user_xlastname) as Creator,
            CONCAT(
                CASE
                    WHEN TIMESTAMPDIFF(DAY, incident_startdate, incident_enddate) > 0 THEN CONCAT(TIMESTAMPDIFF(DAY, incident_startdate, incident_enddate), " days ")
                    ELSE ""
                END,
                CASE
                    WHEN TIMESTAMPDIFF(HOUR, incident_startdate, incident_enddate) % 24 > 0 THEN CONCAT(TIMESTAMPDIFF(HOUR, incident_startdate, incident_enddate) % 24, " hrs ")
                    ELSE ""
                END,
                CONCAT(TIMESTAMPDIFF(MINUTE, incident_startdate, incident_enddate) % 60, " mins")
            ) AS Downtime');
            return $ir->groupBy('incident_report.incident_id')->orderBy('incident_report.incident_startdate', 'desc')->get();
        }
    public function irdatatable (Request $request){
        {
            $ir = $this->irdata();
            $datatables = Datatables::of($ir)
            
            ->addColumn('incident_number',function($ir)
            {
                $column = '<a class="tableData" href="'.route('ir.index',$ir->incident_number).'"><b>'.$ir->incident_number.'</b></a>';
                return $column;
            })
                
            ->setRowId(function($ir){
                return $ir->incident_id;
            });
    
            $rawcolumns = ['incident_number','incident_title','Creator','incident_type','incident_affected','incident_location','incident_action','incident_startdate','incident_enddate','Duration','serverip'];
            return $datatables->rawColumns($rawcolumns)->make(true);
        }
    }
    public static function getfullname ($username = ""){
        $val = UserBasic::where('user_xusern',$username)->first();
        $firstname =$val->user_xfirstname ?? '';
        $lastname = $val->user_xlastname ?? '';
        $name = $firstname.' '.$lastname ;
        return $name;
    }
    public static function adding_comment(Request $request){
        {
            try {
                
                $user = $request->user;
                $comment = $request->comment;
                $irnumber = $request->irnumber;


                $irc = new IncidentReportComment;
                $irc->incident_id           =           $irnumber;
                $irc->comment               =           $comment;
                $irc->comment_user_id       =           $user;
                $irc->save();
                return "success";  
            } 
            catch (\Throwable $th) {
                alert()->error($th->getMessage())->showConfirmButton('Return', '#fa0031');
                //dd($request);
                return  alert()->error($th->getMessage())->showConfirmButton('Return', '#fa0031');
            }
           
        }
    }

    public static function ir_email($to_email,$incident_id, $incident_tonotify){
        $toUser = UserEmail::where('email_xuser',$to_email)->first();
        $send_email = $incident_tonotify;
        //$to_email = $incident_tonotify; 
        $email = new IR($incident_id);
        Mail::to($send_email)
        ->send($email);
    }
    public function downloadExcel(Request $request){
    $exportColumns = $request->input('export_columns');

    // Retrieve the selected column names
    $columns = collect($exportColumns)->pluck('data')->toArray();

    // Retrieve the filtered data based on the selected columns
    $filteredData = IncidentReports::get($columns);

    return Excel::download(new IncidentReportsExport($filteredData), 'IncidentReports.xlsx');
    }

    public function getDataOwner(Request $request)
{
    $selectedSystem = $request->input('system');

    $data = DB::table('inventory_system')
        ->leftJoin('user_basic', 'user_basic.user_xusern', 'inventory_system.data_owner')
        ->leftJoin('user_basic as system_owner', 'system_owner.user_xusern', 'inventory_system.system_owner')
        ->where('inventory_system.project', $selectedSystem)
        ->selectRaw('CONCAT(user_basic.user_xfirstname, " ", user_basic.user_xlastname) as dataowner, CONCAT(system_owner.user_xfirstname, " ", system_owner.user_xlastname) as systemowner, inventory_system.server_ip') // Removed alias for server_ip
        ->first();

    if ($data) {
        return response()->json([
            'dataowner' => $data->dataowner ?? 'Not Found',
            'systemowner' => $data->systemowner ?? 'Not Found',
            'serverip' => $data->server_ip ?? 'Not Found', // Updated property name
        ]);
    } else {
        return response()->json([
            'dataowner' => 'Not Found',
            'systemowner' => 'Not Found',
            'serverip' => 'Not Found',
        ]);
    }
}





public function getSystemOwner(Request $request)
{
    $selectedSystem = $request->input('system');

    $systemowner = DB::table('inventory_system')
        ->where('inventory_system.project', $selectedSystem)
        ->selectRaw('server_ip')
        ->first();

    if ($systemowner) {
        return response()->json([
            'server_ip' => $server_ip->server_ip
        ]);
    } else {
        return response()->json([
            'server_ip' => 'Not Found'
        ]);
    }
}

public function getDataOwnerName(Request $request)
{
    $selectedSystem = $request->input('system');

    $dataowner = DB::table('user_basic')
        ->where('user_basic.user_xusern', $selectedSystem)
        ->selectRaw('CONCAT(user_basic.user_xfirstname, " ", user_basic.user_xlastname) as dataowner')
        ->first();

    if ($dataowner) {
        return response()->json([
            'dataowner' => $dataowner->dataowner
        ]);
    } else {
        return response()->json([
            'dataowner' => 'Not Found',
        ]);
    }
}

public function getServerIPs(Request $request)
{
    try {
        // Get the selected project from the AJAX request
        $selectedProject = $request->input('project');

        // Fetch the server IPs for the selected project
        $serverIPs = InventorySystem::where('project', $selectedProject)->orderBy('server_ip', 'asc')->get();

        // Return the server IPs in JSON format
        return response()->json($serverIPs);
    } catch (\Exception $e) {
        // Handle any exceptions and return an error response
        return response()->json(['error' => 'Server error'], 500);
    }
}

public function serverinfo(Request $request)
{
    try {
        $selectedServerIP = $request->input('serverIP');

        // Fetch the server information based on the selected Server IP
        $serverInfo = InventorySystem::where('server_ip', $selectedServerIP)->first();

        // Return the server information in JSON format
        return response()->json([
            'rack_address' => $serverInfo->rack_address,
            'server' => $serverInfo->server,
            'server_name' => $serverInfo->server_name,
            'os' => $serverInfo->os,
        ]);
    } catch (\Exception $e) {
        // Handle any exceptions and return an error response
        return response()->json(['error' => 'Server error'], 500);
    }
}

}

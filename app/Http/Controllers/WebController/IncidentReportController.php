<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IncidentReports;
use App\Models\IncidentReportComment;
use App\Models\IncidentReportsAttachments;
use App\Models\SystemIncidentType;
use App\Models\SystemApps;
use App\Models\ModLocation;
use App\Models\UserBasic;
use App\Models\InventorySystem;
use App\Models\IncidentProjects;
use App\Models\IncidentProjectIP;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DB;
use Mail;
use App\Mail\IR;
use Maatwebsite\Excel\Facades\Excel;

class IncidentReportsController extends Controller
{
    

    public function index(Request $request)
    {
    
        $incidenttype = SystemIncidentType::orderBy('type_name', 'asc')->get();
        $systemapps = SystemApps::orderBy('syst_xname', 'asc')->get();
        $location = ModLocation::orderBy('loc_name', 'asc')->get();
        $ipaddress = InventorySystem::orderby('server_ip','asc')->get();

    
        $incidentReports = IncidentReports::with('userbasic', 'useremp')->get();
        $user = auth()->user()->status_xuser;
        session(['message'=>' Incident Management Report ('.$user.') ' ,'icon'=>'bi bi-exclamation-triangle-fill','icon-code'=>'']);
        return view('pages.incident_management_report.index', compact('incidenttype', 'systemapps', 'location', 'incidentReports'));
    }

    public function show ($id)
    {
        session(['message'=>' Incident Management Report' ,'icon'=>'bi bi-exclamation-triangle-fill','icon-code'=>'']);
        $ir = IncidentReports::with('userbasic','irUploads','useremp','ip')->where('incident_id', $id)->first();
        $title = IncidentReports::with('userbasic')->where('incident_title',$id)->first();
        $ircom = IncidentReportComment::with('userbasic')->where('incident_id',$id)->orderBy('updated_at','desc')->get();
        $user = auth()->user()->status_xuser;
        session(['message'=>' Incident Management Report ('.$user.') ' ,'icon'=>'bi bi-exclamation-triangle-fill','icon-code'=>'']);

        return view ('pages.incident_management_report.show', compact(['ir','user','ircom']));
        
    }

    public function report ()
    {
        session(['message'=>' Incident Management Report' ,'icon'=>'bi bi-exclamation-triangle-fill','icon-code'=>'']);
        
        return view ('pages.incident_management_report.reports');
        
    }
    
    public function create ()
    {
        $dataowner = DB::table('user_basic')
        ->selectRaw('user_xusern as dataowner, user_xfirstname, user_xlastname')
        ->orderby('user_xusern','asc')
        ->get();
        $incidenttype = SystemIncidentType::orderby('type_name','asc')->get();
        $systemapps = SystemApps::orderby('syst_xname','asc')->get();
        $location = ModLocation::orderby('loc_name','asc')->get();
        $inventory = InventorySystem::orderby('project','asc')->get();
        $user = auth()->user()->status_xuser;
        $username = UserBasic::orderby('user_xusern','asc')->get();
        session(['message'=>' Incident Management Report ('.$user.') ' ,'icon'=>'bi bi-exclamation-triangle-fill','icon-code'=>'']);

        return view ('pages.incident_management_report.create', compact(['incidenttype','systemapps','location','dataowner','user','inventory']));
    }

    public function edit ($id)
    {
        $incidenttype = SystemIncidentType::orderby('type_name','asc')->get();
        $ir = IncidentReports::find($id);
        $systemapps = SystemApps::orderby('syst_xname','asc')->get();
        $location = ModLocation::orderby('loc_name','asc')->get();
        $user = auth()->user()->status_xuser;
        session(['message'=>' Incident Management Report ('.$user.') ' ,'icon'=>'bi bi-exclamation-triangle-fill','icon-code'=>'']);

        return view ('pages.incident_management_report.edit', compact(['incidenttype','systemapps','location','ir','user']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'irtitle' => 'required',
            'irtype' => 'required',
            'iraffected' => 'required',
            'irlocation' => 'required',
            'pci' => 'required',
            'irdetect' => 'required',
            'irinvest' => 'required',
            'iraction' => 'required',
            'irrecom' => 'required',
            'irnotifyto' => 'required|email',
            'Downtime' => 'required'
        ]);
        try {
            $ir = new IncidentReports;
            $ir->incident_number = self::irnumber();
            $ir->incident_title = $request->irtitle;
            $ir->incident_creator = Auth::user()->status_xuser;
            $ir->incident_location = $request->irlocation;
            $ir->incident_pci = $request->pci;
            $ir->incident_affected = $request->iraffected;
            $ir->incident_type = $request->irtype;
            $ir->incident_down = $request->Downtime;
            $ir->incident_startdate = $request->irstart;
            $ir->incident_enddate = $request->irend;
            $ir->incident_detect = $request->irdetect;
            $ir->incident_inves = $request->irinvest;
            $ir->incident_action = $request->iraction;
            $ir->incident_recom = $request->irrecom;
            $ir->incident_tonotify = $request->irnotifyto;
            $ir->save();
            
            $ipcount = 0;
                while ($ipcount < count($request->server_ip)) {
                    $projectip = new IncidentProjectIP;
                   $projectip->ip_incident_number = $ir->incident_number;
                    $projectip->project_ip = $request->server_ip[$ipcount] ?? null;
                    $projectip->ip_number = $this->projectnumber($ir->incident_number);
                    $projectip->server_model = $request->servermod[$ipcount] ?? null; 
                    $projectip->server_name = $request->servername[$ipcount] ?? null; 
                    $projectip->os = $request->os[$ipcount] ?? null; 
                    $projectip->rack_address = $request->rackadd[$ipcount] ?? null; 
                
                    // Add validation to skip saving if necessary values are missing
                    if (
                       $projectip->project_ip !== null &&
                        $projectip->server_model !== null &&
                      $projectip->server_name !== null &&
                       $projectip->os !== null &&
                       $projectip->rack_address !== null
                   ) {
                       $projectip->save();
                   }
                   
                   $ipcount++;
                }

            if ($request->hasFile('filename')) {
                $path = storage_path('IncidentReports/' . $ir->incident_number);
                if (!File::exists($path)) {
                    Storage::disk('ir_upload')->makeDirectory($ir->incident_number);
                }

                $uploadedFiles = $request->file('filename');
                foreach ($uploadedFiles as $uploadedFile) {
                    $irupl = new IncidentReportsAttachments;
                    $irupl->ir_id = $ir->incident_number;
                    $irupl->filename = $uploadedFile->getClientOriginalName();
                    $irupl->save();
                    Storage::disk('ir_upload')->put('/' . $ir->incident_number . '/' . $uploadedFile->getClientOriginalName(), File::get($uploadedFile));
                }
            }

            // Uncomment the following lines if you want to send an email
            // $ir_email = new IR($ir);
            // Mail::to($request->irnotifyto)->send(new IR($ir));

            alert()->success('Report #' . $ir->incident_number . ' has been submitted for review')
                ->showConfirmButton(
                    $btnText = '<a class="add-padding" href="' . route('ir.show', $ir->incident_id) . '">View Request</a>',
                    $btnColor = '#fa0031',
                    ['className'  => 'no-padding'],
                )->autoClose(false);

            return redirect()->back();
        } catch (\Throwable $th) {
            alert()->error($th->getMessage())->showConfirmButton('Return', '#fa0031');
            return redirect()->back();
        }
    }

    public function update(Request $request, $incident_id)
    {
        
        $request->validate([
           'irtitle' => 'required',
            'irtype' => 'required',
            'iraffected' => 'required',
            'irlocation' => 'required',
            'irstart' => 'required',
            'irend' => 'required',
            'pci' => 'required',
            'irdetect' => 'required',
            'irinvest' => 'required',
            'iraction' => 'required',
            'irrecom' => 'required',
            'irnotifyto' => 'required|email',
            'Downtime' => 'required'
        ]);
        try {
            
            $ir = IncidentReports::where('incident_id',$incident_id)->first();
            $ir->incident_title         =$request->irtitle;
            $ir->incident_type          =$request->irtype;
            $ir->incident_affected      =$request->iraffected;
            $ir->incident_location      =$request->irlocation;
            $ir->incident_pci           =$request->pci;
            $ir->incident_down          =$request->Downtime;
            $ir->incident_startdate     =$request->irstart;
            $ir->incident_enddate       =$request->irend;
            $ir->incident_detect        =$request->irdetect;
            $ir->incident_inves         =$request->irinvest;
            $ir->incident_action        =$request->iraction;
            $ir->incident_recom         =$request->irrecom;
            $ir->incident_tonotify      =$request->irnotifyto;
            $ir->save();

            

            if($request->hasFile('filename'))
            {
                $path = storage_path('IncidentReports/'.$ir->Incident_number);
                if(!File::exists($path)) {
                    Storage::disk('ir_upload')->makeDirectory($ir->Incident_number);
                }
                $uploadcount = 0;
                while ($uploadcount < count($request->filename)) {
                    $filename = $request->filename[$uploadcount];
                    $ir = new IncidentReportsAttachments;
                    $ir->ir_id          =$ir->Incident_number;
                    $ir->filename       =$filename->getClientOriginalName();
                    $ir->save();
                    Storage::disk('it_upload')->put( '/'.$ir->Incident_number.'/'.$filename->getClientOriginalName(),File::get($filename));
                    $uploadcount++;
                }
            }
            
                alert()->success('Incident Report' ,$ir->incident_number.' has been Updated')
                ->showConfirmButton(
                    $btnText = '<a class="add-padding" href="'.route('ir.show',$ir->incident_id).'">View Request</a>',
                    $btnColor = '#fa0031',
                    ['className'  => 'no-padding'], 
                )->autoClose(false);
                return redirect()->back();

            }
        catch (\Throwable $th) 
        {
            alert()->error($th->getMessage())->showConfirmButton('Return', '#fa0031');
            return redirect()->back();
        }
    }
    public function ir_email($to_email, $incident_id)
        {
            $incident = IncidentReports::find($incident_id);
            $email_data = [
                'incident_title' => $incident->incident_title,
                'incident_creator' => $incident->incident_creator,
                'incident_type' => $incident->incident_type,
                'incident_affected' => $incident->incident_affected,
                'incident_location' => $incident->incident_location,
                'incident_pci' => $incident->incident_pci,
                'incident_down' => $incident->incident_down,
                'incident_startdate' => $incident->incident_startdate,
                'incident_enddate' => $incident->incident_enddate,
                'incident_detect' => $incident->incident_detect,
                'incident_inves' => $incident->incident_inves,
                'incident_action' => $incident->incident_action,
                'incident_recom' => $incident->incident_recom,
            ];
        
            Mail::to($to_email)->send(new IR($email_data));
        }
    public static function irnumber() {
       
        $ir = IncidentReports::orderBy('incident_id','desc')->first();

        $last_ticket = $ir->incident_number;
		$tick_date	= substr($last_ticket, 0, 10);
		$tick_date_now = date('Y-m-d');
		$tick_pre 	= "-IR-";
		$tick_start = "000";
		$tick_num	= substr($last_ticket, 14, 17);
		
		if ($tick_date == $tick_date_now) { 
			$tick_d1 = $tick_date; 
			$tick_d2 = $tick_pre;
			$tick_dx = $tick_num + 1;
			$tick_d3 = str_pad($tick_dx, 3, '0', STR_PAD_LEFT);
		} else { 
			$tick_d1 = $tick_date_now;
		    $tick_d2 = $tick_pre;
			$tick_d3 = $tick_start;
		}
		 return $tick_dt = $tick_d1.$tick_d2.$tick_d3;
    }

    public static function projectnumber($incident_number) {
       
        $projnum = IncidentProjectIP::orderBy('ip_number','desc')->first();
      
        $last_ticket = $projnum->ip_number;
		$project_number	= substr($last_ticket, 0, 17); 
		$tick_date_now = date('Y-m-d');
		$tick_pre 	= "-";
		$tick_start = "00";
		$item_num	= substr($last_ticket, 18, 20);
		
		if ($incident_number == $project_number) { 

			$tick_d2 = $tick_pre;
			$tick_dx = $item_num + 1;
			$tick_d3 = str_pad($tick_dx, 2, '0', STR_PAD_LEFT);

		} else { 
			$tick_d1 = $tick_date_now;
		    $tick_d2 = $tick_pre;
			$tick_d3 = $tick_start;
		}
		return $tick_dt = $incident_number.$tick_pre.$tick_d3;
    }
    }
    
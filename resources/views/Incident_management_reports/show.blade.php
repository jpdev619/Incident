@extends('pages.main-content')
@section('css')
<style>
    td {
        min-width: 250px;
        word-wrap: break-word;
    }
    .tr-comment {
        line-height: 5vh;
    }
    .icon {
        color: red;
    }
</style>
@endsection

@section('content')
@if($ir)
<div class="container-fluid">
    <br>
    <div class="row">
        <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0 mt-0">
            <label></label>
        </div>
        <div class="col-12 col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 p-0 mt-0 text-right">
            @if($ir->incident_creator == $user)
            <button type="button" class="btn btn-default btn-block  border-1 btn-outline-secondary" onclick="location.href='{{route('ir.edit',$ir->incident_id)}}'"><i class="fas fa-edit"></i> EDIT</button>
            @endif
            <button type="button" class="btn btn-default btn-block  border-1 btn-outline-secondary d-none" onclick><i class="fas fa-print "></i> PRINT</button>
            <button type="button" class="btn btn-default btn-block  border-1 btn-outline-secondary" onclick="location.href='{{route('ir.index')}}'"><i class="fas fa-arrow-circle-left"></i> RETURN TO LIST</button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 ">
        <h5 class="text-truncate col-10 mt-3"> <span class="far fa-user icons"></span> <b> Incident Creator</b></h5>
        <hr class="col-10"></hr>
        <div class="row">
            <div class="dcol-10 col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xl-10 ">
                <div class="form-group">
                    <table class="table table-bordered table-responsive table-fw-widget " id="3">
                        <tbody>
                            <tr>
                                <td>Creator</td>
                                <td><b>{{$ir->userbasic->user_xfirstname.' '.$ir->userbasic->user_xlastname}}</b></td>
                            </tr>
                            <tr>
                                <td>Company</td>
                                <td><b>{{$ir->useremp->emp_xcompany}}</b></td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td><b>{{$ir->useremp->emp_xdepartment}}</b></td>
                            </tr>
                            <tr>
                                <td>Job Title</td>
                                <td><b>{{$ir->useremp->emp_xjobtitle}}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 ">
        <h5 class="text-truncate col-10 mt-3"><span class="far fa-building icons"></span> <b> Incident Details</b></h5>
        <hr class="col-10"></hr>
        <div class="row">
            <div class="col-10 col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xl-10 ">
                <div class="form-group">
                    <table class="table table-bordered table-responsive table-fw-widget text-wrap " id="">
                        <tbody>
                            <tr>
                                <td>Title</td>
                                <td><b>{{$ir->incident_title}}</b></td>
                            </tr>
                            <tr>
                                <td>Location</td>
                                <td><b>{{$ir->incident_location}}</b></td>
                            </tr>
                            <tr>
                                <td>Project</td>
                                <td><b>{{$ir->incident_affected}}</b></td>
                            </tr>
                            <tr>
                                <td>Incident Type</td>
                                <td><b>{{$ir->incident_type}}</b></td>
                            </tr>
                            <tr>
                                <td>Notify to</td>
                                @if (filter_var($ir->incident_tonotify, FILTER_VALIDATE_EMAIL))
                                <td><b>{{ $ir->incident_tonotify }}</b></td>
                                @else
                                <td><b>{{ $ir->notify->user_xfirstname.' '.$ir->notify->user_xlastname }}</b></td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-15 col-xl-15 ">
    <h5 class="text-truncate col-10 mt-3"> <span class="icon bi bi-exclamation-triangle-fill"></span><b> Incident Report</b></h5>
    <hr class="col-11 col-xs-11 col-sm-11"></hr>
    <div class="row col-11 col-xs-11 col-sm-11">
        <table class="table table-hover table-responsive table-fw-widget " id="tblItems">
            <thead>
                <tr class="text-center border-0">
                    <td class="col-md-1 col-2 col-xs-2 col-sm-2 border-0 text-center">IP address</td>
                    <td class="col-md-1 col-2 col-xs-2 col-sm-2 border-0 text-center">Server Model</td>
                    <td class="col-md-1 col-2 col-xs-2 col-sm-2 border-0 text-center">Server Name</td>
                    <td class="col-md-1 col-2 col-xs-2 col-sm-2 border-0 text-center">OS</td>
                    <td class="col-md-1 col-2 col-xs-2 col-sm-2 border-0 text-center">Rack Address</td>
                </tr>
            </thead>
            <tbody>
                @foreach($ir->ip as $ip)
                    <tr id="{{$ip->ip_incident_number}}">
                        <br>
                        <td class="col-md-1">
                            <input type="text" class="form-control text-center" value="{{$ip->project_ip}}" readonly>
                        </td>
                        <td class="col-md-1">
                            <input type="text" class="form-control text-center" value="{{$ip->server_model}}" readonly>
                        </td>
                        <td class="col-md-1">
                            <input type="text" class="form-control text-center" value="{{$ip->server_name}}" readonly>
                        </td>
                        <td class="col-md-1">
                            <input type="text" class="form-control text-center" value="{{$ip->os}}" readonly>
                        </td>
                        <td class="col-md-1">
                            <input type="text" class="form-control text-center" value="{{$ip->rack_address}}" readonly>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-11">
    <div class="form-group">
        <table class="table table-bordered table-responsive table-fw-widget " id="" style="margin: 0 auto;">
            <tbody>
                <tr>
                    <td><b>Incident Detected</b></td>
                    <td>{{$ir->incident_detect}}</td>
                </tr>
                <tr>
                    <td><b>Investigation</b></td>
                    <td>{{$ir->incident_inves}}</td>
                </tr>
                <tr>
                    <td><b>Action</b></td>
                    <td>{{$ir->incident_action}}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 ">
                <table class="table table-bordered table-responsive table-fw-widget " id="" style="margin: 0 auto;">
                    <tbody>
                        <tr>
                            <td><b>PCI</b></td>
                            <td>{{$ir->incident_pci}}</td>
                        </tr>
                        <tr>
                            <td><b>Down</b></td>
                            <td>{{$ir->incident_down}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 ">
                <table class="table table-bordered table-responsive table-fw-widget " id="" style="margin: 0 auto;">
                    <tbody>
                        <tr>
                            <td><b>Start Date</b></td>
                            <td><b>End Date</b></td>
                            <td><b>Duration</b></td>
                        </tr>
                        <tr>
                            <td>{{$ir->incident_startdate}}</td>
                            <td>{{$ir->incident_enddate}}</td>
                            <td>
                                @php
                                if (!empty($ir->incident_startdate) && !empty($ir->incident_enddate)) {
                                    $startDate = \Carbon\Carbon::parse($ir->incident_startdate);
                                    $endDate = \Carbon\Carbon::parse($ir->incident_enddate);
                                    $duration = $startDate->diff($endDate)->format('%m Mon %d Days, %h Hrs, %i Min');
                                } else {
                                    $duration = 'N/A';
                                }
                                @endphp
                                {{ $duration }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 ">
            <table class="table table-bordered table-responsive table-fw-widget " id="" style="margin: 0 auto;">
                <tbody>
                    <tr>
                        <td><b>Recommendation</b></td>
                        <td>{{$ir->incident_recom}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <BR></BR>
    <div class="row">
        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <h5 class="text-truncate col-10 mt-3"><span class="fas fa-paperclip icons"></span><b>Attachments & Additional Details</b></h5>
            <hr class="col-11 col-xs-11 col-sm-11"></hr>
            <div class="row">
                <div class="col-11 col-xs-11 col-sm-11 col-md-11 col-lg-11 col-xl-11">
                    <nav class="nav" style="display:inline-block">
                        @foreach($ir->irUploads as $ir_upload)
                        <a class="nav-link" onclick="file_show('{{$ir_upload->ir_id}}','{{$ir_upload->filename}}')" aria-current="page" href="#">
                            <div class="card border-1 btn-outline-danger border-danger">
                                <div class="card-body text-center">
                                    <span class="bx bx-file-blank bx-lg"></span>
                                    <br>
                                    {{ $ir_upload->filename}}
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </nav>
                </div>
            </div>
            <br>
        </div>
    </div>
</div>
</div>
<br><br><br><br><br><br>
</div>

@endif
@endsection

@section('scripts')
<script type="text/javascript">
var rowCount = $("#tblItems tbody>tr").length;
$(document).ready(function() {

    $('#btn_comment').click(function() {
        $(this).attr('disabled', true);
        var comment = $('#stg_comment').val();
        var user = '{{ $user }}';
        var irnumber = '{{ isset($ir) ? $ir->incident_id : '' }}';

        if (comment != "") {
            $.ajax({
                url: "{{route('ir.comment')}}",
                type: "post",
                data: {
                    comment: comment,
                    user: user,
                    irnumber: irnumber,
                },
                success: function(result) {
                    if (result == "success") {
                        Swal.fire({
                            title: 'Adding Comment..',
                            text: 'Your comment is successfully Added',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#fa0031',
                        }).then((result) => {
                            if (result.value) {
                                location.reload();
                            }
                        });
                    }
                },
                error: function(result) {
                    alert(result);
                }
            });
        } else {
            Swal.fire({
                title: 'Comment is required',
                text: 'Please add comment.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#fa0031',
            }).then((result) => {
                if (result.value) {
                    location.reload();
                }
            });
        }
    });


});

function file_show(ir_id, filename) {
    window.open("{{asset('storage/IncidentReports/')}}/" + ir_id + "/" + filename);
}
</script>
@endsection

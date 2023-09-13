@extends('pages.main-content')
@section('css')
<style>
.tbl_div {
}

.icon {
    color: red;
}

.btnn {
    color: green;
}

</style>
@endsection
@section('content')
<div class="container-fluid">
    <br>
    <form method="POST" action="{{route('ir.update',['ir' => $ir->incident_id])}}" enctype="multipart/form-data">
    @method('put')
    @csrf
        <div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-11">
            <h5 class="text-truncate col-10 mt-3"><span class="icon bi bi-exclamation-triangle-fill"></span> <b> New Report</b></h5>
            <hr class="col-10"></hr>
                <div class="form-group row">
                    <label for="incident-type" class="col-sm-2 col-form-label"><b>Incident Title :</b></label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control @error('irtitle') is-invalid @endif" name="irtitle" value="{{$ir->incident_title}}">
                        @error('irtitle') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="irtype" class="col-sm-2 col-form-label"><b>Incident type :</b></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control @error('irtype') is-invalid @endif" value="{{$ir->incident_type}}" name="irtype" id="irtype" class="form-control" placeholder="">
                        @error('irtype') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="irtyiraffectedpe" class="col-sm-2 col-form-label"><b>Affected :</b></label>
                    <div class="col-sm-8">
                        <input type="text"  class="form-control @error('iraffected') is-invalid @endif" value="{{$ir->incident_affected}}" name="iraffected" id="iraffected" class="form-control" placeholder="">
                        @error('iraffected') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="irlocation" class="col-sm-2 col-form-label"><b>Location :</b></label>
                    <div class="col-sm-8">
                        <select name="irlocation" id="irlocation" class="form-select for_label @error('irtype') is-invalid @enderror">
                            <option disabled selected>--Select--</option>
                            @if($location)
                            @foreach($location as $location)
                            <option value='{{$location->loc_name}}' @if($ir->incident_location == $location->loc_name) selected @endif> {{$location->loc_name}}</option>
                            @endforeach
                            @endif
                        </select>
                        @error('irlocation') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div> 
                <br>
                <div class="row">
                    <div class="col-5 col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xl-10">
                    <label for="exampleFormControlSelect1" class="form-label text-truncate col-10"><b>Start / End Date</b></label>
                    <table class="table table-responsive table-fw-widget " id="tblItems" >
                        <tbody>
                                <tr>
                                    <td class="col-md-1 text-center">
                                        <label class="form-label" id="row_no"><b>Start:</b></label>
                                    </td>
                                    <td class="col-md-1">
                                        <input type="datetime-local" name="irstart" id="irstart" class="form-control @error('irstart') is-invalid @enderror" value="{{$ir->incident_startdate}}">
                                        @error('irstart') 
                                        <div class="invalid-feedback">Required*</div>
                                        @enderror
                                    </td>
                                    <td class="col-md-1 text-center">
                                        <label class="form-label" id="row_no"><b>PCI-DDS:</b></label>
                                    </td>
                                    <td class="col-md-1 @error('pci') is-invalid @enderror">
                                        <div>
                                            <label for="male">Yes</label>
                                            <input type="radio" id="Yes" name="pci" value="Yes" @if(old('pci') == "Yes" || $ir->incident_pci == "Yes") checked @endif>
                                            <label for="female">No</label>
                                            <input type="radio" id="No" name="pci" value="No" @if(old('pci') == "No" || $ir->incident_pci == "No") checked @endif>
                                            @error('pci') 
                                            <div class="invalid-feedback" style="display: block;">Please select an option</div>
                                            @enderror
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="col-md-1  text-center">
                                    <label class="form-label" id="row_no"><b>End:</b></label></td>
                                    <td class="col-md-1">
                                        <input type="datetime-local" name="irend" id="irend" class="form-control @error('irend') is-invalid @enderror" value="{{$ir->incident_enddate}}" >
                                        @error('irend') 
                                        <div class="invalid-feedback">Required*</div>
                                        @enderror
                                    </td>
                                    <td class="col-md-1  text-center">
                                    <label class="form-label" id="row_no"><b>Downtime:</b></label>
                                    </td>
                                    <td class="col-md-1 @error('Downtime') is-invalid @enderror ">
                                        <div>
                                            <label for="male">Yes</label>
                                            <input type="radio" id="Yes" name="Downtime" value="Yes" @if(old('Downtime') == "Yes" || $ir->incident_down == "Yes") checked @endif>
                                            <label for="female">No</label>
                                            <input type="radio" id="No" name="Downtime" value="No" {@if(old('Downtime') == "No" || $ir->incident_down == "No") checked @endif}>
                                            @error('Downtime') 
                                            <div class="invalid-feedback" style="display: block;">Please select an option</div>
                                            @enderror
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="incident-type" class="col-sm-2 col-form-label"><b>Detection :</b></label>
                    <div class="col-sm-8">
                    <textarea type="text" class="form-control @error('irdetect') is-invalid @endif" name="irdetect" value="">{{$ir->incident_detect}}</textarea>
                        @error('irdetect') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="incident-type" class="col-sm-2 col-form-label"><b>Investigation :</b></label>
                    <div class="col-sm-8">
                    <textarea type="text" class="form-control @error('irinvest') is-invalid @endif" name="irinvest" value="">{{$ir->incident_inves}}</textarea>
                        @error('irinvest') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="incident-type" class="col-sm-2 col-form-label"><b>Action :</b></label>
                    <div class="col-sm-8">
                    <textarea type="text" class="form-control @error('iraction') is-invalid @endif" name="iraction" value="">{{$ir->incident_action}}</textarea>
                        @error('iraction') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="incident-type" class="col-sm-2 col-form-label"><b>Recommendations :</b></label>
                    <div class="col-sm-8">
                    <textarea type="text" class="form-control @error('irrecom') is-invalid @endif" name="irrecom" value="">{{$ir->incident_recom}}</textarea>
                        @error('irrecom') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="incident-type" class="col-sm-2 col-form-label"><b>Notify to :</b></label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control @error('irnotifyto') is-invalid @endif" name="irnotifyto" value="{{$ir->incident_tonotify}}">
                        @error('irnotifyto') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
            </div>
        </div>
         <div class="row">
           <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
                <div class="row">
                    <div class="col-6 col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2 ">
                        <div class="form-group">
                          <button type="submit" class="btn btn-allcard btn-block col-10">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<br><br><br><br><br>    
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
  $('.irstart').datepicker();

});
$( function() {
    var irtype = [
        @foreach($incidenttype as $irtype)
            "{{$irtype->type_name}}",
        @endforeach
    ];
    $( "#irtype" ).autocomplete({
        source: irtype,
        minLength: 0
    });
});

$( function() {
    var availableLocations = [
        @foreach($systemapps as $sysapps)
            "{{$sysapps->syst_xname}}",
        @endforeach
    ];
    $( "#iraffected" ).autocomplete({
        source: availableLocations,
        minLength: 0
    });
});
</script>
@endsection

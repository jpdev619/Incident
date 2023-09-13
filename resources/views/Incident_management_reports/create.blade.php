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
    <form method="post" action="{{route('ir.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-11">
                <h5 class="text-truncate col-10 mt-3"><span class="icon bi bi-exclamation-triangle-fill"></span> <b> New Report</b></h5>
                <hr class="col-10"></hr>
                <div class="form-group row">
                    <label for="incident-type" class="col-sm-2 col-form-label"><b>Incident Title :</b></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control @error('irtitle') is-invalid @endif" id="irtitle" name="irtitle" value="{{old('irtitle')}}">
                        @error('irtitle') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="iraffected" class="col-sm-2 col-form-label"><b>Project:</b></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <select name="iraffected" id="iraffected" class="form-select for_label @error('iraffected') is-invalid @enderror">
                                <option disabled selected>--Select--</option>
                                @if($systemapps)
                                @foreach($systemapps as $systemapp)
                                <option value='{{$systemapp->syst_xname}}' @if(old('iraffected') == $systemapp->syst_xname) selected @endif> {{$systemapp->syst_xname}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        @error('iraffected') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="irtype" class="col-sm-2 col-form-label"><b>Incident Type :</b></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <select name="irtype" id="irtype" class="form-select for_label @error('irtype') is-invalid @enderror">
                                <option disabled selected>--Select--</option>
                                @if($incidenttype)
                                @foreach($incidenttype as $incidenttype)
                                <option value='{{$incidenttype->type_name}}' @if(old('irtype') == $incidenttype->type_name) selected @endif> {{$incidenttype->type_name}}</option>
                                @endforeach
                                @endif
                            </select>
                            @error('irtype') 
                            <div class="invalid-feedback">Required*</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                
                <div class="form-group row">
                    <label for="irlocation" class="col-sm-2 col-form-label"><b>Location :</b></label>
                    <div class="col-sm-8">
                        <select name="irlocation" id="irlocation" class="form-select for_label @error('irlocation') is-invalid @enderror">
                            <option disabled selected>--Select--</option>
                            @if($location)
                            @foreach($location as $location)
                            <option value='{{$location->loc_name}}' @if(old('irlocation') == $location->loc_name) selected @endif> {{$location->loc_name}}</option>
                            @endforeach
                            @endif
                        </select>
                        @error('irlocation') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div> 
                <br>
                <div class="form-group row">
                    <a href="#" class="p-2" id="addrow">
                        <span class="fas fa-plus"></span> Add Equipment
                    </a>
                    <div class="col-11 col-xs-11 col-sm-11 col-md-11 col-lg-11 col-xl-11">
                       <hr class="col-12">
                       <table id="equipmentTable" class="table table-striped">
                           <thead>
                               <tr>
                                   <th>Server IP</th>
                                   <th>Server</th>
                                   <th>Server Name</th>
                                   <th>OS</th>
                                   <th>Rack Address</th>
                               </tr>
                           </thead>
                           <tbody>
                               <tr>
                                   <td>
                                      <div class="server-spec row">
                                           <div class="col-sm-9">
                                               <div class="custom-dropdown">
                                                   <select name="server_ip[]" class="form-select for_label server_ip_select @error('irtype') is-invalid @enderror" onchange="displayServerInfo(this)">
                                                       <option disabled selected>--Select a project first--</option>
                                                   </select>
                                               </div>
                                           </div>
                                       </div>
                                   </td>
                                   <td><b><input type="text" class="form-control server_input" name="servermod[]" readonly></b></td>
                                   <td><input type="text" class="form-control server_name_input" name="servername[]" readonly></td>
                                   <td><b><input type="text" class="form-control os_input" name="os[]" readonly></b></td>
                                   <td><b><input type="text" class="form-control rack_address_input" name="rackadd[]" readonly></b></td>
                                   <td>
                                        <button type="button" class="btn btn-sm btn-danger remove-row">Remove</button>
                                    </td>
                               </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr class="col-11">
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
                                        <input type="datetime-local" name="irstart" id="irstart" class="form-control @error('irstart') is-invalid @enderror" value="{{old('irstart')}}">
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
                                            <input type="radio" id="Yes" name="pci" id="pci" value="Yes" {{ old('pci') == 'Yes' ? 'checked' : '' }}>
                                            <label for="female">No</label>
                                            <input type="radio" id="No" name="pci" id="pci" value="No" {{ old('pci') == 'No' ? 'checked' : '' }}>
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
                                        <input type="datetime-local" name="irend" id="irend" class="form-control @error('irend') is-invalid @enderror" value="{{old('irend')}}" >
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
                                            <input type="radio" id="Yes" name="Downtime" id="Downtime" value="Yes" {{ old('Downtime') == 'Yes' ? 'checked' : '' }}>
                                            <label for="female">No</label>
                                            <input type="radio" id="No" name="Downtime" id="Downtime" value="No" {{ old('Downtime') == 'No' ? 'checked' : '' }}>
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
                        <textarea type="text" class="form-control @error('irdetect') is-invalid @endif"  name="irdetect" id="irdetect" value="{{old('irdetect')}}"></textarea>
                        @error('irdetect') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="incident-type" class="col-sm-2 col-form-label"><b>Investigation :</b></label>
                    <div class="col-sm-8">
                        <textarea type="text" class="form-control @error('irinvest') is-invalid @endif" name="irinvest" id="irinvest" value="{{old('irinvest')}}"></textarea>
                        @error('irinvest') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="incident-type" class="col-sm-2 col-form-label"><b>Action :</b></label>
                    <div class="col-sm-8">
                        <textarea type="text" class="form-control @error('iraction') is-invalid @endif" name="iraction" id="iraction" value="{{old('iraction')}}"></textarea>
                        @error('iraction') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="incident-type" class="col-sm-2 col-form-label"><b>Recommendations :</b></label>
                    <div class="col-sm-8">
                        <textarea type="text" class="form-control @error('irrecom') is-invalid @endif" name="irrecom" id="irrecom" value="{{old('irrecom')}}"></textarea>
                        @error('irrecom') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label for="incident-type" class="col-sm-2 col-form-label"><b>Notify to :</b></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control @error('irnotifyto') is-invalid @endif" name="irnotifyto" id="irnotifyto" value="javillanueva@allcardtech.com.ph">
                        @error('irnotifyto') 
                        <div class="invalid-feedback">Required*</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
                    <h5 class="text-truncate col-10 mt-3"> <span class="fas fa-paperclip icons"></span> <b> Attachments</b></h5>
                    <div class="row">
                        <div class="col-11 col-xs-11 col-sm-11 col-md-11 col-lg-11 col-xl-11 ">
                            <div class="form-group">
                                <div class="file-loading"> 
                                    <input id="input-b6" name="filename[]" type="file" accept=".jpg,.jpeg,.png,.pdf,.docx,.xls,.xlsx,.pptx" multiple>
                                </div>
                            </div>
                            <small class="text-muted p-2">(Maximum individual file size is 10MB)</small>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="row">
                    <div class="col-6 col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <button type="submit" id="" class="btn btn-allcard btn-block col-10">Submit</button>
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

    $(document).ready(function() {
        $("#input-b6").fileinput({
            showUpload: false,
            dropZoneEnabled: false,
            maxFileCount: 15,
            maxFileSize: 25000,
            inputGroupClass: "input-group-sm"
        });
    });

    $(document).ready(function() {
        $('#iraffected').change(function() {
            var selectedSystem = $(this).val();

            // Make an AJAX request to retrieve the specific data owner
            $.ajax({
                url: "{{route('get-data-owner')}}",
                method: 'GET',
                data: { system: selectedSystem },
                success: function(response) {
                    $('#dataownerLabel').text('Data Owner : ' + response.dataowner);
                    $('#systownerLabel').text('System Owner : ' + response.systemowner);
                },
                error: function() {
                    $('#dataownerLabel').text('Data Owner: Not Found');
                }
            });
        });
    });

    $(document).ready(function() {
        $('#dataowner').change(function() {
            var selectedSystem = $(this).val();

            // Make an AJAX request to retrieve the specific data owner
            $.ajax({
                url: "{{route('dataownername')}}",
                method: 'GET',
                data: { system: selectedSystem },
                success: function(response) {
                    $('#usern').val(response.dataowner);
                },
                error: function() {
                    $('#usern').val('Data Owner: Not Found');
                }
            });
        });

        $('#sysowner').change(function() {
            var selectedSystem = $(this).val();

            // Make an AJAX request to retrieve the specific data owner
            $.ajax({
                url: "{{route('dataownername')}}",
                method: 'GET',
                data: { system: selectedSystem },
                success: function(response) {
                    $('#usern1').val(response.dataowner);
                },
                error: function() {
                    $('#usern1').val('Data Owner: Not Found');
                }
            });
        });
    });

    $(document).ready(function() {
        $("#iraffected").on("change", function() {
            var selectedProject = $(this).val();
            fetchServerIPs(selectedProject);
        });

        $(document).on("change", ".server_ip_select", function() {
            displayServerInfo(this);
        });
    });

    function fetchServerIPs(selectedProject) {
        $.ajax({
            url: "{{ route('serverip') }}",
            type: "GET",
            data: { project: selectedProject },
            success: function(response) {
                var options = '<option disabled selected>--Select--</option>';
                $.each(response, function(key, value) {
                    options += '<option value="' + value.server_ip + '">' + value.server_ip + '</option>';
                });
                $(".server_ip_select").html(options);
            },
            error: function(error) {
                console.error("Error fetching server IPs:", error);
            }
        });
    }

    function displayServerInfo(selectElement) {
    var selectedServerIP = $(selectElement).val();
    var row = $(selectElement).closest("tr"); // Get the parent row of the select element

    $.ajax({
        url: "{{ route('servername') }}",
        method: 'GET',
        data: { serverIP: selectedServerIP },
        success: function(response) {
            row.find(".rack_address_input").val(response.rack_address);
            row.find(".server_input").val(response.server);
            row.find(".server_name_input").val(response.server_name);
            row.find(".os_input").val(response.os);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
            row.find(".rack_address_input").val('Rack Address: Not Found');
            row.find(".server_input").val('Server: Not Found');
            row.find(".server_name_input").val('Server Name: Not Found');
            row.find(".os_input").val('OS: Not Found');
        }
    });
}


    $(document).ready(function() {
        $("#addrow").click(function() {
            var newRow = $("#equipmentTable tbody tr:last").clone();
            newRow.find("select").val(null);
            $("#equipmentTable tbody").append(newRow);

            $(document).on("click", ".remove-row", function() {
            $(this).closest("tr").remove();
            });
        });

        $('#equipmentTable').dataTable({
            ajax: {
                url: "{{ route('serverip') }}",
                dataSrc: ''
            },
            columns: [
                { data: 'server_ip' },
                { data: 'server' },
                { data: 'server_name' },
                { data: 'os' },
                { data: 'rack_address' }
            ]
        });
    });
</script>

@endsection

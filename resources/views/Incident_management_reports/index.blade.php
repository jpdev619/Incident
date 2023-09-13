@extends('pages.main-content')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
@include('layouts.datatables-css')
@endsection
@section('content')
<div class="container-fluid shadow py-2 px-5 main-f">
    <br>
    <form>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                            <label class="form-label" data-bs-toggle="modal" data-bs-target="#modal_">
                                <a href="#"><i class="bi bi-file-earmark-ruled-fill" ></i><b>Search/Filter</b></a>
                            </label>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                        </div>

                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <div class="form-check">

                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <div class="form-check">

                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

                            <div class="form-check">

                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 ">
                            <button type="button" class="btn btn-block btn-allcard col-12" onclick="location.href='{{route('ir.create')}}'"><span class="fas fa-plus fa-sm"></span>  Report Incident</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="col-12">
                <div class="card-body">
                    <table class="table table-hover table-responsive table-fw-widget " id="table4" style="overflow-x: auto">
                        <thead>
                            <tr>
                                <td width="12%"><b>IR #</b></td>
                                <td width="12%"><b>Creator</b></td>
                                <td width="15%"><b>Title</b></td>
                                <td><b>Type</b></td>
                                <td><b>Server IP</b></td>
                                <td><b>Location</b></td>
                                <td width=""><b>Start Date</b></td>
                                <td width=""><b>End Date</b></td>
                                <td width=""><b>Downtime</b></td>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-4">
                <button type="button" id="downloadRecordsBtn" class="btn btn-allcard">Download Records</button>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="modal_filter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
        <div class="modal-body">
  <div class="form-group">
    <label for="filterTitle">Incident Title:</label>
    <input type="text" class="form-control" id="filterTitle">
  </div>
  <div class="form-group">
    <label for="filterType">Type:</label>
    <input type="text" class="form-control" id="filterType">
  </div>
  <div class="form-group">
    <label for="filterLocation">Location:</label>
    <input type="text" class="form-control" id="filterLocation">
  </div>
</div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <br><br>
        <div class="row py-1">
            <div class="col-6 col-lg-3">
                <button type="button" name="" id="btn_search" class="btn btn-allcard w-100" btn-lg btn-block">SEARCH</button>
            </div>
            <div class="col-6 col-lg-3">
                <button type="button" name="" id="btn_clear" class="btn btn-default btn-md btn-block border-1 btn-outline-secondary w-100">CLEAR ALL</button>
            </div>
        </div>
      <br>
    </div>
    </div>
  </div>
</div>


@endsection
@section('scripts')

@section('scripts')
    @include('layouts.datatables-scripts')
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.colVis.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {
            $('#header-toggle').click();

            if ($('#assigned').is(':checked')) {
                $('#assigned').change();
            } else {
                loadDatatable();
            }

            var table;

            function initializeDataTable() {
    table = $('#table4').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: 'Export Excel',
                className: 'btn btn-allcard',
                filename: 'IncidentReports',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] 
                }
            }
        ]
    });
}

            $('#downloadRecordsBtn').click(function() {
    downloadRecords();
});

function downloadRecords() {
    var table = $('#table4').DataTable();

    if (table && table.data().length > 0) {
        var columnHeaders = table.columns().header().toArray().map(function(header) {
            var text = $(header).text();
            // Escape double quotes with another double quote
            text = text.replace(/"/g, '""');
            return '"' + text + '"';
        });

        var csvContent = "data:text/csv;charset=utf-8,";

        csvContent += columnHeaders.join(",") + "\r\n";

        var data = table.data().toArray();
        for (var i = 0; i < data.length; i++) {
            var rowData = [];
            for (var j = 0; j < data[i].length; j++) {
                var cellData = data[i][j];
                if (typeof cellData === 'string') {
                    cellData = cellData.replace(/"/g, '""');
                    cellData = '"' + cellData + '"';
                }
                rowData.push(cellData);
            }
            csvContent += rowData.join(",") + "\r\n";
        }

        var encodedUri = encodeURI(csvContent);
        var link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', 'Incident_records.csv');

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } else {
        console.error('DataTable instance is empty or not available.');
    }
}


            $('#table4 tbody').on('click', 'tr', function() {
                var url = '{{route("ir.show",["ir" => ":irtitle"])}}';
                url = url.replace(':irtitle',$(this).closest('tr').attr('id'));

                location.href = url;
            });

            $('#checkAll').change(function() {
                filter_tickmark();
            });

            $('#assigned').change(function() {
                filter_tickmark();
            });

            $('#open').change(function() {
                filter_tickmark();
            });

            function filter_tickmark() {
                var showreq = "";
                if ($('#checkAll').is(':checked')) showreq = "1";
                else showreq = "none";
                var assigned = "";
                if ($('#assigned').is(':checked')) assigned = "1";
                else assigned = "none";
                var open = "";
                if ($('#open').is(':checked')) open = "1";
                else open = "none";

                loadDatatable("none", "none", showreq, assigned, open);
            }

            function loadDatatable() {
                var url = "{!! route('ir.datatables') !!}";
                var columns = [
                    { data: 'incident_number', searchable: true },
                { data: 'Creator', searchable: true },
                { data: 'incident_title', searchable: true },
                { data: 'incident_affected', searchable: true },
                { data: 'serverip'},
                { data: 'incident_location' , searchable: true },
                { data: 'incident_startdate' },
                { data: 'incident_enddate' },
                { data: 'Downtime' }
                ];

                load_datables('#table4', url, columns, initializeDataTable);
            }
        });
    </script>
@endsection
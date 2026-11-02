@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
   <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <title>Stock Details</title>
    <style>
        table {
  border-collapse: collapse;
  border: 2px solid rgb(200,200,200);
  letter-spacing: 1px;
  font-size: 1rem;
}

td, th {
  border: 1px solid rgb(190,190,190);
  padding: 10px 20px;
}

th {
  background-color: rgb(235,235,235);
}

td {
  text-align: center;
}

tr:nth-child(even) td {
  background-color: rgb(250,250,250);
}

tr:nth-child(odd) td {
  background-color: rgb(245,245,245);
}

caption {
  padding: 10px;
}
.input {
  max-width: 190px;
  background-color: #f5f5f5;
  color: #242424;
  padding: .15rem .5rem;
  min-height: 40px;
  border-radius: 4px;
  outline: none;
  border: none;
  line-height: 1.15;
  box-shadow: 0px 10px 20px -18px;
}

input:focus {
  border-bottom: 2px solid #5b5fc7;
  border-radius: 4px 4px 2px 2px;
}

input:hover {
  outline: 1px solid lightgrey;
}

h3 {
            
        }
        caption {
            caption-side: top;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            margin-bottom: 10px;
        }
    </style>

    <style>
        button {
  font-family: inherit;
  font-size: 14px;
  background: linear-gradient(to bottom, #4dc7d9 0%,#66a6ff 100%);
  color: white;
  padding: 0.8em 1.2em;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  border-radius: 25px;
  box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
  transition: all 0.3s;
}

button:hover {
  transform: translateY(-3px);
  box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.3);
}

button:active {
  transform: scale(0.95);
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
}

button span {
  display: block;
  margin-left: 0.4em;
  transition: all 0.3s;
}

button svg {
  width: 18px;
  height: 18px;
  fill: white;
  transition: all 0.3s;
}

button .svg-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.2);
  margin-right: 0.5em;
  transition: all 0.3s;
}

button:hover .svg-wrapper {
  background-color: rgba(255, 255, 255, 0.5);
}

button:hover svg {
  transform: rotate(45deg);
}


    </style>
    <style>
        body { 
            margin: 20px; 
        } 

        .container { 
            max-width: 1000px; 
            margin: 0 auto; 
            background-color: #f9f9f9; 
            padding: 60px; 
            border-radius: 8px; 
            box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
        } 

        @media print {
            @page {}
            .print-button {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div id="printableArea" class="container mt-4">
                    <br>
                        <button onclick="printTablefun()">
                        <div class="svg-wrapper-1">
                          <div class="svg-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/>
                              </svg>
                          </div>
                        </div>
                        {{-- <span>P</span> --}}
                      </button>
                   <div class="table-responsive">
                  <table id="LateRedeemtable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="styled-table text-center">
                    <th>Customer Comment</th>
                     <th>Receipt No</th>
                    <th>NIC</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>  
                    <th>Date</th>
                    <th>Amount</th>
                </tr>
            </thead>
           @foreach ($recipts as $key => $receipts)
          <tr class="styled-table text-center">
              <td>
                  @if ($receipts->isForfeit == 1)
                      <button1 type="button" class="btn btn-danger view-receipt">
                          <i class="fa fa-ban"></i> Forfeited
                      </button1>
                  @else
                      <button type="button" class="btn btn-info view-receipt"
                              data-receipt="{{ $receipts->Receipt_Number }}"
                              data-bs-toggle="modal" data-bs-target="#exampleModal">
                          <i class="fa fa-eye"></i> View
                      </button>
                  @endif
              </td>
              <td>{{ $receipts->Receipt_Number }}</td>
              <td>{{ $receipts->Customer_NIC }}</td>
              <td>{{ $receipts->Customer_Name }}</td>
              <td>{{ $receipts->Customer_Address }}</td>
              <td>{{ $receipts->Customer_Phone }}</td>
              <td>{{ $receipts->Receipt_Number }}</td>
              <td class="text-end">{{ $receipts->Amount }}</td>
          </tr>
          @endforeach

        </table>
        </div>


                <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Receipt Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalBody">
        Loading...
      </div>
    </div>
  </div>
</div>


                      <br>
                      <br>
             
              </div>
        </div>
    </div>
</div>






<script>
    $(document).ready(function () {
        $('#LateRedeemtable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            responsive: true,
            lengthMenu: [10, 25, 50, 100],
        });
    });

    function printTablefun() {
        var divToPrint = document.getElementById("LateRedeemtable");
        var newWin = window.open("");

        newWin.document.write(`
            <html>
                <head>
                    <style>
                        table {
                            border-collapse: collapse;
                            width: 100%;
                            font-size: 1.2rem;
                        }
                        th, td {
                            border: 1px solid #dddddd;
                            text-align: center;
                            padding: 8px;
                        }
                        th {
                            background-color: #f2f2f2;
                        }
                    </style>
                </head>
                <body>
                    ${divToPrint.outerHTML}
                </body>
            </html>
        `);
        newWin.print();
        newWin.close();
    }
</script>

<script>
    $(document).ready(function () {
        $('.view-receipt').on('click', function () {
            let receiptNumber = $(this).data('receipt');

            $.ajax({
                url: '/get-receipt-details',
                method: 'GET',
                data: { receipt_number: receiptNumber },
                success: function (response) {
                    $('#modalBody').html(response); // Load the content into modal body
                },
                error: function () {
                    $('#modalBody').html('<p class="text-danger">Failed to load details.</p>');
                }
            });
        });
    });
</script>


<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/js/feather.min.js"></script>
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/datatables.min.js"></script>
        <script src="assets/js/script.js"></script>
        <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
        <script src="assets/plugins/apexchart/chart-data.js"></script>
        <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    </div>
</body>
@endsection
</html>
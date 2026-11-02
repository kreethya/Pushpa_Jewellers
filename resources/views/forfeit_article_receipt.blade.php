@extends('layouts.topnavbar')
@extends('layouts.sidebar')
<style>
.forfeit-heading {
    text-align: center;
    font-weight: bold;
    font-size: 26px;
    color: #000107;
    margin-bottom: 20px;
    position: relative;
    display: inline-block;
}
.forfeit-heading::after {
    content: "";
    display: block;
    width: 60%;
    height: 3px;
    background-color: #00010e;
    margin: 8px auto 0;
    border-radius: 2px;
}
</style>
@section('content')
<div class="main-wrapper">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="card">
                @if (session('success'))
    <script>
        window.onload = function() {
            alert("{{ session('success') }}");
        };
    </script>
@endif

@if (session('error'))
    <script>
        window.onload = function() {
            alert("{{ session('error') }}");
        };
    </script>
@endif
{{-- 
                <form method="get" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="from_date" class="form-label">From Date:</label>
                        <input type="date" class="form-control" name="from_date" id="from_date" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="to_date" class="form-label">To Date:</label>
                        <input type="date" class="form-control" name="to_date" id="to_date" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form> --}}

                <div class="card-body">
                 <h3 class="forfeit-heading">Forfeit Article's</h3>
                    <div class="table-responsive">
                  <form method="POST" action="{{ route('items.storeFromForfeit') }}">
                        @csrf
                        <table id="forfeitTable" class="table table-bordered table-striped dt-responsive nowrap">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Check</th>
                                    <th>Receipt Number</th>
                                    <th>Category</th>
                                    <th>Articles</th>
                                    <th>Condition</th>
                                    <th>Karatage</th>
                                    <th>Weight</th>
                                    <th>Quantity</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recipts as $index => $rec)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <input class="form-check-input" type="checkbox" name="selected_ids[]" value="{{ $rec->id }}">
                                        </td>
                                        <td>{{ $rec->Receipt_Number }}</td>
                                        <td>{{ $rec->category }}</td>
                                        <td>{{ $rec->Item_description }}</td>
                                        <td>{{ $rec->Brand }}</td>
                                        <td>{{ $rec->Make }}</td>
                                        <td>{{ $rec->Weight }}</td>
                                        <td>{{ $rec->QTY }}</td>
                                        <td>{{ $rec->purchasePrice }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-4"></div>
                             <div class="col-md-4"><button type="submit" class="btn btn-success">Save Selected</button></div>
                              <div class="col-md-4"></div>
                        </div>

                        
                    </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function() {
        $('#forfeitTable').DataTable({
            responsive: true
        });
    });
</script>

<script>
    var dateObj = new Date();
    document.getElementById('to_date').value = dateObj.toISOString().slice(0, 10);

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

@endsection
@extends('layouts.topnavbar')
@extends('layouts.sidebar')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Customer Management</h3>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Validation Errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Search Card -->
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('customerUpdatestatus') }}">
                    <div class="row">
                        <div class="col-md-8">
                            <label>Customer NIC</label>
                            <input type="text"
                                   name="nic"
                                   class="form-control"
                                   placeholder="Enter NIC"
                                   value="{{ $nic ?? '' }}"
                                   required>
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="{{ route('customerUpdatestatus') }}"
                               class="btn btn-secondary">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Customer Result -->
        @if(isset($customer))
            @if($customer)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">Edit Customer Details</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('customerUpdatestatus.update', $customer->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-12">
                                    <h6 class="text-primary mb-3">Basic Information</h6>
                                </div>

                                <div class="col-md-2" style="display: none">
                                    <div class="form-group">
                                        <label>Title <span class="text-danger">*</span></label>
                                        <select name="title" class="form-control" >
                                            <option value="">Select</option>
                                            <option value="Mr" {{ $customer->Title == 'Mr' ? 'selected' : '' }}>Mr</option>
                                            <option value="Mrs" {{ $customer->Title == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                            <option value="Miss" {{ $customer->Title == 'Miss' ? 'selected' : '' }}>Miss</option>
                                            <option value="Dr" {{ $customer->Title == 'Dr' ? 'selected' : '' }}>Dr</option>
                                            <option value="Rev" {{ $customer->Title == 'Rev' ? 'selected' : '' }}>Rev</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2" style="display: none">
                                    <div class="form-group">
                                        <label>Gender <span class="text-danger">*</span></label>
                                        <select name="gender" class="form-control" >
                                            <option value="">Select</option>
                                            <option value="Male" {{ $customer->Gender == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ $customer->Gender == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ $customer->Gender == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" class="form-control"
                                               value="{{ $customer->First_name }}" required>
                                    </div>
                                </div>

                                <div class="col-md-4" style="display: none">
                                    <div class="form-group">
                                        <label>Middle Name</label>
                                        <input type="text" name="middle_name" class="form-control"
                                               value="{{ $customer->Middle_name }}">
                                    </div>
                                </div>

                                <div class="col-md-4" style="display: none">
                                    <div class="form-group">
                                        <label>Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" class="form-control"
                                               value="{{ $customer->Last_name }}" >
                                    </div>
                                </div>

                                <div class="col-md-6" style="display: none">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" name="name" class="form-control"
                                               value="{{ $customer->Name }}">
                                    </div>
                                </div>

                                <div class="col-md-4" style="display: none">
                                    <div class="form-group">
                                        <label>Customer Code</label>
                                        <input type="text" name="code" class="form-control"
                                               value="{{ $customer->Code }}" >
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="col-md-12 mt-3">
                                    <h6 class="text-primary mb-3">Contact Information</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact 1 <span class="text-danger">*</span></label>
                                        <input type="text" name="contact_1" class="form-control"
                                               value="{{ $customer->Contact_1 }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact 2</label>
                                        <input type="text" name="contact_2" class="form-control"
                                               value="{{ $customer->Contact_2 }}">
                                    </div>
                                </div>


                                <!-- Address Information -->
                                <div class="col-md-12 mt-3">
                                    <h6 class="text-primary mb-3">Address Information</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address 1 <span class="text-danger">*</span></label>
                                        <textarea name="address_1" class="form-control" rows="2" required>{{ $customer->Address_1 }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6" style="display: none">
                                    <div class="form-group">
                                        <label>City 1 <span class="text-danger">*</span></label>
                                        <input type="text" name="city_1" class="form-control"
                                               value="{{ $customer->City_1 }}" >
                                    </div>
                                </div>

                                <div class="col-md-6" style="display: none">
                                    <div class="form-group">
                                        <label>Address 2</label>
                                        <textarea name="address_2" class="form-control" rows="2">{{ $customer->Address_2 }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6" style="display: none">
                                    <div class="form-group">
                                        <label>City 2</label>
                                        <input type="text" name="city_2" class="form-control"
                                               value="{{ $customer->City_2 }}">
                                    </div>
                                </div>

                                <!-- Identification Information -->
                                <div class="col-md-12 mt-3">
                                    <h6 class="text-primary mb-3">Identification Information</h6>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NIC <span class="text-danger">*</span></label>
                                        <input type="text" name="nic" class="form-control"
                                               value="{{ $customer->NIC }}" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Driving License</label>
                                        <input type="text" name="driving_license" class="form-control"
                                               value="{{ $customer->Driving_license }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Passport</label>
                                        <input type="text" name="passport" class="form-control"
                                               value="{{ $customer->Passport }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Other Identifications</label>
                                        <textarea name="other_identifications" class="form-control" rows="2">{{ $customer->Other_identifications }}</textarea>
                                    </div>
                                </div>

                                <!-- Status and Additional Fields -->
                                <div class="col-md-12 mt-3">
                                    <h6 class="text-primary mb-3">Status & Additional Information</h6>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control" required>
                                            <option value="1" {{ $customer->Status == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $customer->Status == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>





                                <!-- Action Buttons -->
                                <div class="col-md-12 mt-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Update Customer
                                    </button>
                                    <a href="{{ route('customerUpdatestatus') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-danger mt-4">
                    ❌ No customer found for this NIC.
                </div>
            @endif
        @endif

    </div>
</div>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="assets/plugins/apexchart/chart-data.js"></script>
<script src="assets/js/script.js"></script>
@endsection

<style>
    .customer-card {
        background: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .stat-box input {
        font-size: 1.3rem;
        font-weight: bold;
        text-align: center;
        border: none;
        background: #f8f9fa;
    }

    .stat-box label {
        width: 100%;
        font-weight: 600;
        text-align: center;
    }

    .customer-label {
        font-weight: 600;
        color: #555;
    }
</style>

@if(isset($customer_get) && $customer_get->count() > 0)

    @foreach ($customer_get as $customer)

        <div class="customer-card">
            <div class="row g-3 align-items-center">

                <!-- Hidden Customer ID -->
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                <!-- Late (Optional – Set 0 if not calculated) -->
                <div class="col-md-1 stat-box">
                    <label class="text-danger">
                        LATE
                        <input type="text"
                               class="form-control text-danger"
                               value="{{ $customer->LateRedim ?? 0 }}"
                               readonly>
                    </label>
                </div>

                <!-- Pending -->
                <div class="col-md-2 stat-box">
                    <label class="text-warning">
                        PENDING
                        <input type="text"
                               class="form-control text-warning"
                               value="{{ $pending_count }}"
                               readonly>
                    </label>
                </div>

                <!-- Redeemed -->
                <div class="col-md-2 stat-box">
                    <label class="text-success">
                        REDEEMED
                        <input type="text"
                               class="form-control text-success"
                               value="{{ $redeemed_count }}"
                               readonly>
                    </label>
                </div>

                <!-- Customer Name -->
                <div class="col-md-3">
                    <label class="customer-label">Customer Name</label>
                    <input type="text"
                           class="form-control form-control-lg" name="customer_name"
                           value="{{ $customer->First_name }} {{ $customer->Last_name }}"
                           readonly>
                </div>

                <!-- Address -->
                <div class="col-md-4">
                    <label class="customer-label">Customer Address</label>
                    <input type="text" name="customer_address"
                           class="form-control form-control-lg"
                           value="{{ $customer->Address_1 }}"
                           readonly>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="customer_contact_1" value="{{ $customer->Contact_1 }}">
                <input type="hidden" name="first_name" value="{{ $customer->First_name }}">
                <input type="hidden" name="middle_name" value="{{ $customer->Middle_name }}">
                <input type="hidden" name="last_name" value="{{ $customer->Last_name }}">

            </div>
        </div>

    @endforeach

@else
    <div class="alert alert-warning text-center">
        No customer found for this NIC.
    </div>
@endif
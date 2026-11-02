<head>
    <style>
    .styled-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .styled-table thead th {
        background-color: #4183ec;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        padding: 12px;
        border-bottom: 2px solid #dee2e6;
    }

    .styled-table tbody tr {
        background-color: #f9f9f9;
        transition: background-color 0.3s;
    }

    .styled-table tbody tr:hover {
        background-color: #e0f0ff;
    }

    .styled-table td {
        padding: 12px;
        vertical-align: middle;
        color: #333;
        font-size: 15px;
    }

    .styled-table th,
    .styled-table td {
        text-align: center;
    }
    </style>
</head>
<div class="col ">
    <div class="row">
        <div class="col">
            <input type="hidden" id="operator" name="operator" value="{{ Auth::user()->name }}" required>

            @foreach ($receiptData as $receipt)
            <input type="hidden" id="r_number" name="r_number" value="{{ $receipt['Receipt_Number'] }}" required>
            @endforeach

            @foreach ($receiptData as $receipt)
            <input type="hidden" id="i_number" name="i_number" value="{{ $receipt['Invoice_Number'] }}" required>
            <input type="hidden" id="total_weight" name="total_weight" value="{{ $receipt['Total_Weight'] }}" required>
            <input type="hidden" id="pawn_weight" name="pawn_weight" value="{{ $receipt['Pawn_Weight'] }}" required>
            @endforeach

            <label for="receipt_type">Receipt Type :
                <input class="form-control"  type="text" placeholder="Receipt type"
                    name="receipt_type"
                    @foreach ($receiptData as $receipt)
                    value="{{$receipt['Receipt_Type']}}"
                    @endforeach
                    id="receipt_type" readonly/>
            </label>
        </div>
        <div class="col">
                <label for="date">Date :
                    <input class="form-control" type="date"
                    id="date"
                    @foreach ($receiptData as $receipt)
                    value="{{ $receipt['Receipt_Date'] }}"
                    @endforeach
                    name="forfeit_date" required>
                </label>
        </div>
        <div class="col">
            <label for="forfeit_no">Forfeit Number :
                <input class="form-control" type="text" placeholder="Forfeit Number:"
                value="{{ $maxRedeem+1 }}"
                id="forfeit_no" name="forfeit_no" required>
            </label>
        </div>
        <div class="col">
            @if($pawnType == "Pawn")
            <label for="pawn_receipt_type"><span class="text-success">Pawn Receipt Type :</span>
                <input class="form-control text-success" type="text" placeholder="Pawn Receipt Type:"
                value="{{ $pawnType }}"
                id="pawn_receipt_type" name="pawn_receipt_type" required readonly>
            </label>
            @elseif($pawnType == "Opening_Pawn")
            <label for="pawn_receipt_type"><span class="text-primary">Pawn Receipt Type :</span>
                <input class="form-control text-primary" type="text" placeholder="Pawn Receipt Type:"
                value="{{ $pawnType }}"
                id="pawn_receipt_type" name="pawn_receipt_type" required readonly>
            </label>

            @endif
        </div>
    </div>
</div>
<h5 class="mt-3 mb-2">Receipt Info</h5>
<table class="styled-table text-center" id="dynamicAdded">
    <thead class="thead-light">
        <tr>
            <th>Final Date</th>
            <th>Receipt Date Time</th>
            <th>Receipt Valided Days</th>
            <th>Period (Days)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                    <p>
                @foreach ($receiptData as $receipt)
                   {{ $receipt['Final_date'] }}
                @endforeach
                </p>
            </td>
            <td>
                <p>
                @foreach ($receiptData as $receipt)
                   {{ $receipt['Receipt_Date'] }}
                @endforeach
                </p>
            </td>
            <td>
                <p id="date-final-day"></p>
            </td>
            <td>
                <p id="date-period"></p>
            </td>
        </tr>
    </tbody>
</table>
<br>

<div class="row">

  <div class="col-md-6">
        <h5 class="mt-3 mb-2">Customer Info</h5>
       <table class="styled-table text-center">
            <thead>
                <tr>
                    <th>Name</th>
                    @foreach ($customerData as $customer)
                    <td><h7>{{ $customer['First_name'] }} {{ $customer['Middle_name'] }} {{ $customer['Last_name'] }}</h7></td>
                    @endforeach
                </tr>
                <tr>
                    <th>Address</th>
                    @foreach ($customerData as $customer)
                    <td><h6>{{ $customer['Address_1'] }}</h5></td>
                    @endforeach
                </tr>
                <tr>
                    <th>NIC</th>
                    @foreach ($customerData as $customer)
                    <td><h5>{{ $customer['NIC'] }}</h5></td>
                    @endforeach
                </tr>
                <tr>
                    <th>Contact</th>
                    @foreach ($customerData as $customer)
                    <td><h5>{{ $customer['Contact_1'] }}</h5></td>
                    @endforeach
                </tr>
            </thead>
        </table>
  </div>
  
 <div class="col-md-6">
    <h5 class="mt-3 mb-2">Customer comment</h5>
    <table class="styled-table text-center">
        <thead>
            <tr>
                <th>Comment</th>
                <th>Comment Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($MPawnfeedback as $customer)
            <tr>
                <td>{{ $customer['feedback'] }}</td>
                <td>{{ $customer['Current_date'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="row" style="align-content: center;">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-outline-info form-control" type="button"
                data-bs-toggle="modal" data-bs-target="#viewArticleDetailsModel">
                <i class="far fa-eye me-1"></i>
                    View Article Details
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="col-md-12">
            <button class="btn btn-outline-info form-control" type="button"
            data-bs-toggle="modal" data-bs-target="#viewPaymentHistoryModel">
            <i class="far fa-eye me-1"></i>
                Payment History
            </button>
        </div>
    </div>
</div>
</div>


<br>

<br>
<h5 class="mt-3 mb-2">Forfeit Details</h5>
<table class="table table-bordered text-center" id="dynamicAdded">
    <tbody class="thead-light">
        <tr>
            <th>Original Pawn Amount</th>
            <td>
                @foreach ($receiptData as $receipt)
                <p>
                    <input class="form-control text-center"  type="text" placeholder="Original Pawn Amount"
                    name="original_pawn_amount"
                    value="{{$receipt['Amount']}}"
                    id="original_pawn_amount" readonly/>
                </p>
                @endforeach
            </td>
        </tr>
        <tr>
            <th>Paid Interest</th>
            <td>
                <p>
                    <input class="form-control text-center" id="interest" type="text" placeholder="Paid Interest"
                    name="paid_interest" value="" readonly/>
                </p>
            </td>
        </tr>
        <tr>
            <th>Document Charges</th>
            <td>
                <p>
                    <input class="form-control text-center" id="document_charges" type="text" placeholder="Document Charges"
                    name="document_charges" value="" readonly/>
                </p>
            </td>
        </tr>
        <tr>
            <th>Other Charges</th>
            <td>
                <p>
                    <input class="form-control text-center" id="other_charges" type="text" placeholder="Other Charges"
                    name="other_charges" value="" readonly/>
                </p>
            </td>
        </tr>
        <tr>
            <th>Advance Balance</th>
            <td>
                <p>
                    <input class="form-control text-center" id="advance_balance" type="text" placeholder="Advance Balance"
                    name="advance_balance" value="" readonly/>
                </p>
            </td>
        </tr>
    </tbody>
</table>
<br>





{{-- Functions for date and time  calculation --}}
<script>
    $(document).ready(function () {

        // form default date set for today
        document.getElementById('date').valueAsDate = new Date();

        calculateFinalDate();
        interestCalculations()


        //getting document charges
        let doc_chargers = 0 ;

            @foreach ($receiptTypeData as $receiptType)
            doc_chargers += {{ $receiptType['documentCharges'] }};
            @endforeach;

            $('#document_charges').val(doc_chargers);


         // run interest calculation when change the amount field
         $('#date, #date-period').on('keyup, change',function(e){
                e.preventDefault();

                calculateFinalDate();
                interestCalculations()
        })

        // Function to calculate and display the final date
        function calculateFinalDate() {
            let receiptDate = new Date("@foreach ($receiptData as $receipt){{ $receipt['Receipt_Date'] }}@endforeach");
            let finalDate = new Date("@foreach ($receiptData as $receipt){{ $receipt['Final_date'] }}@endforeach");

            let today_str = $('#date').val();
            let today_format = new Date(today_str);

            // Calculate days between today and receiptDate
            let date_period_days = Math.floor((today_format - receiptDate) / (1000 * 60 * 60 * 24));
            document.getElementById("date-period").textContent = date_period_days;

            // Calculate days between finalDate and receiptDate
            let date_final_days = Math.floor((finalDate - receiptDate) / (1000 * 60 * 60 * 24));
            document.getElementById("date-final-day").textContent = date_final_days;

            // Show alert if today is before the final date
            if (date_period_days < date_final_days) {
                let remainingDays = date_final_days - date_period_days;
                alert("This receipt is not a Forfeit Receipt. It has more days remaining: " + remainingDays + " days.");
            }
        }



        function interestCalculations(){
            let receiptDate = new Date("@foreach ($receiptData as $receipt){{ $receipt['Receipt_Date'] }}@endforeach");
            let today_str = $('#date').val();
            let today_format = new Date(today_str);

            // Calculate the time difference in months
            const monthDiff = (receiptDate, today_format) => Math.max(0,
            (today_format.getFullYear() - receiptDate.getFullYear()) * 12 - receiptDate.getMonth() + today_format.getMonth());

            let DiffMonths = monthDiff(receiptDate, today_format);

            if (DiffMonths=="0"){
                DiffMonths = 1;
            }

            // getting period details
            let period1 = 0;
            let period2 = 0;
            let period3 = 0;
            @foreach ($receiptTypeData as $receiptType)
                period1 += {{ $receiptType['period1'] }};
            @endforeach;
            @foreach ($receiptTypeData as $receiptType)
                period2 += {{ $receiptType['period2'] }};
            @endforeach;
            @foreach ($receiptTypeData as $receiptType)
                period3 += {{ $receiptType['period3'] }};
            @endforeach;

            // getting rates details
            let rate1 = 0;
            let rate2 = 0;
            let rate3 = 0;
            @foreach ($receiptTypeData as $receiptType)
                rate1 += {{ $receiptType['rate1'] }};
            @endforeach;
            @foreach ($receiptTypeData as $receiptType)
                rate2 += {{ $receiptType['rate2'] }};
            @endforeach;
            @foreach ($receiptTypeData as $receiptType)
                rate3 += {{ $receiptType['rate3'] }};
            @endforeach;



            // Calculate the time difference in milliseconds
            let timeDiffMilliseconds = today_format - receiptDate;
            // Convert milliseconds to days
            let date_range_days = Math.floor(timeDiffMilliseconds / (1000 * 60 * 60 * 24));

            const maxWeeks = 26;

            // let week_rang = (date_range_days/7).toFixed(2);
            if(date_range_days == '0'){
                date_range_days = 1;
            }else{

            }

            let weeks = Math.floor((date_range_days-1 )/ 7);
            console.log(weeks);

            // weeks = Math.min(weeks, maxWeeks);

            // Get the amount 0
            let amount = parseFloat( {{  $receiptData[0]['Amount'] }} ) || 0;

            // Calculate interest based on the number of weeks
            // if(weeks == 0){
            //     weeks = 1;
            // }else{

            // }
            let interestRate = weeks+1;
            let interest = (amount * interestRate) / 100;

            // Ensure the interest is rounded to two decimal places
            interest = interest.toFixed(2);
            $('#interest').val(interest);

            // calculate the interest checking the periods
            // if (week_rang < 1 || date_range_days == "0") {
            //     let interest = ((amount / 100)*1).toFixed(2);
            //     $('#interest').val(interest);

            // } else if (week_rang < 2) {
            //     let interest = ((amount / 100)*2).toFixed(2);
            //     $('#interest').val(interest);

            // }
            // else if (week_rang < 3) {
            //     let interest = ((amount / 100)*3).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 4) {
            //     let interest = ((amount / 100)*4).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 5) {
            //     let interest = ((amount / 100)*5).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 6) {
            //     let interest = ((amount / 100)*6).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 7) {
            //     let interest = ((amount / 100)*7).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 8) {
            //     let interest = ((amount / 100)*8).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 9) {
            //     let interest = ((amount / 100)*9).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 10) {
            //     let interest = ((amount / 100)*10).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 11) {
            //     let interest = ((amount / 100)*11).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 12) {
            //     let interest = ((amount / 100)*12).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 13) {
            //     let interest = ((amount / 100)*13).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 14) {
            //     let interest = ((amount / 100)*14).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 15) {
            //     let interest = ((amount / 100)*15).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 16) {
            //     let interest = ((amount / 100)*16).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 17) {
            //     let interest = ((amount / 100)*17).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 18) {
            //     let interest = ((amount / 100)*18).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 19) {
            //     let interest = ((amount / 100)*19).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 20) {
            //     let interest = ((amount / 100)*20).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 21) {
            //     let interest = ((amount / 100)*21).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 22) {
            //     let interest = ((amount / 100)*22).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 23) {
            //     let interest = ((amount / 100)*23).toFixed(2);
            //     $('#interest').val(interest);

            // }else if (week_rang < 24) {
            //     let interest = ((amount / 100)*24).toFixed(2);
            //     $('#interest').val(interest);

            // }

            // else if (date_range_days > period3) {
            //     let interest = (((amount / 100))*rate3)* DiffMonths .toFixed(2);
            //     $('#interest').val(interest);
            // }

            // Calling the total payment function
            redeemTotalCalculation()
            }


        // Calling the total payment function
        redeemTotalCalculation()
        // Calculate the total payment
        function redeemTotalCalculation(){
            // Calculate the total payment
            let total_pay = 0;
            let amount = parseFloat( {{  $receiptData[0]['Amount'] }} ) || 0;
            let discount = parseFloat($('#redeem_discount').val());
            let interest_to_pay = parseFloat($('#interest').val());
            let document_charges = parseFloat($('#document_charges').val());

            if (discount > 0) {
                total_pay = (amount + interest_to_pay + document_charges) - discount;
                $('#redeem_total').val(total_pay);
            } else {
                total_pay = amount + interest_to_pay + document_charges ;
                $('#redeem_total').val(total_pay);
            }
        }

        // run total pay calculation when change the discount field
        $('#redeem_discount').on('keyup',function(e){
                e.preventDefault();
                redeemTotalCalculation()
        })


    });

</script>




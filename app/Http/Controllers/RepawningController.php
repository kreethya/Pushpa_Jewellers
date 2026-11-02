<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Http\Request;
use App\Models\itemCondition;
use App\Models\karatage;
use App\Models\Recei_Add;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Company;
use App\Models\TPawnDetails;
use App\Models\TPawnSum;
use App\Models\TRedeemSum;
use App\Models\TRepawningSum;
use App\Models\TOpeningPawnSum;
use App\Models\TOpeningPawnDetails;
use App\Models\branchDel;
use Carbon\Carbon;
use App\Models\TPawnTrans;
use App\Models\MPawnfeedback;
use Illuminate\Support\Facades\DB;


class RepawningController extends Controller
{

    public function index()
    {
        $branch_code = auth()->user()->BC;
        $maxRedeemNo = TRedeemSum::where('BC',$branch_code)
        ->orderBy('Redeem_Number', 'desc')
        ->value('Redeem_Number');
        $maxRedeemNos = str_pad($maxRedeemNo, 4, '0', STR_PAD_LEFT);

        $branchData = branchDel::where('bccode',$branch_code)
                    ->paginate(1);

        return view('repawning')
        ->with("branchDetails", $branchData)
        ->with("maxRedeem", $maxRedeemNos);
    }

    // ─────────────────────────────────────────────────────────────
    // Receipt Search function
    // ─────────────────────────────────────────────────────────────
    public function search(Request $request)
    {
        $receiptNo   = $request->search_receipt_no;
        $branch_code = auth()->user()->BC;
        $today_date  = Carbon::today();

        $dataTPawnSum = TPawnSum::where('Receipt_Number', $receiptNo)
            ->where('IsRedeemed', 0)
            ->where('isForfeit', 0)
            ->where('BC', $branch_code)
            ->get();

        $data = $dataTPawnSum;

        if ($data->count() > 0) {

            $cus_nic  = $data->first()->Customer_NIC;
            $cus_data = Customer::where('NIC', $cus_nic)->get();

            $receipt_typ  = $data->first()->Receipt_Type;
            $receipt_data = Recei_Add::where('receiptname', $receipt_typ)->get();

            $maxRedeemNo  = TRedeemSum::where('BC', $branch_code)
                ->orderBy('Redeem_Number', 'desc')
                ->value('Redeem_Number');
            $maxRedeemNos = str_pad($maxRedeemNo, 4, '0', STR_PAD_LEFT);

            $pawn_type   = "Pawn";
            $receiptType = Recei_Add::all();

            $MPawnfeedback = MPawnfeedback::where('Receipt_Number', $receiptNo)
                ->where('BC', $branch_code)
                ->get();

            // ── Karatage pawning rate lookup ──────────────────────────
            $pawnDetails = TPawnDetails::where('Receipt_Number', $receiptNo)
                ->where('BC', $branch_code)
                ->get();

            $karatages    = $pawnDetails->pluck('Karatage')->unique()->filter()->toArray();
            $karatageData = karatage::whereIn('descrption', $karatages)->get();
            // ─────────────────────────────────────────────────────────

            return view('repawningsearch')
                ->with('receiptType',    $receiptType)
                ->with('maxRedeem',      $maxRedeemNos)
                ->with('MPawnfeedback',  $MPawnfeedback)
                ->with('customerData',   $cus_data)
                ->with('receiptTypeData',$receipt_data)
                ->with('pawnType',       $pawn_type)
                ->with('receiptData',    $data)
                ->with('pawnDetails',    $pawnDetails)    // ✅ pawn detail rows
                ->with('karatageData',   $karatageData);  // ✅ karatage rates

        } else {
            return response()->json(['status' => 'not_found']);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Invoice Search function
    // ─────────────────────────────────────────────────────────────
    public function searchInvoice(Request $request)
    {
        $invoiceNo   = $request->search_invoice_no;
        $branch_code = auth()->user()->BC;

        $TOpeningPawnSumdata = TOpeningPawnSum::where('Invoice_Number', $invoiceNo)
            ->where('IsRedeemed', 0)
            ->where('isForfeit', 0)
            ->where('BC', $branch_code)
            ->get();

        $data = $TOpeningPawnSumdata;

        if ($data->count() > 0) {

            $cus_nic  = $data->first()->Customer_NIC;
            $cus_data = Customer::where('NIC', $cus_nic)->get();

            $receipt_typ  = $data->first()->Receipt_Type;
            $receipt_data = Recei_Add::where('receiptname', $receipt_typ)->get();

            $maxRedeemNo  = TRedeemSum::where('BC', $branch_code)
                ->orderBy('Redeem_Number', 'desc')
                ->value('Redeem_Number');
            $maxRedeemNos = str_pad($maxRedeemNo, 4, '0', STR_PAD_LEFT);

            $pawn_type = "Opening_Pawn";

            return view('redeemSearch')
                ->with('maxRedeem',      $maxRedeemNos)
                ->with('customerData',   $cus_data)
                ->with('receiptTypeData',$receipt_data)
                ->with('pawnType',       $pawn_type)
                ->with('receiptData',    $data);

        } else {
            return response()->json(['status' => 'not_found']);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Ticket Search function
    // ─────────────────────────────────────────────────────────────
    public function searchTicket(Request $request)
    {
        $receiptNo   = $request->search_receipt_no;
        $branch_code = auth()->user()->BC;

        $dataTPawnSum = TPawnSum::where('Ticket_Number', $receiptNo)
            ->where('IsRedeemed', 0)
            ->where('isForfeit', 0)
            ->where('BC', $branch_code)
            ->get();

        $data = $dataTPawnSum;

        if ($data->count() > 0) {

            $cus_nic  = $data->first()->Customer_NIC;
            $cus_data = Customer::where('NIC', $cus_nic)->get();

            $receipt_typ  = $data->first()->Receipt_Type;
            $receipt_data = Recei_Add::where('receiptname', $receipt_typ)->get();

            $maxRedeemNo  = TRedeemSum::where('BC', $branch_code)
                ->orderBy('Redeem_Number', 'desc')
                ->value('Redeem_Number');
            $maxRedeemNos = str_pad($maxRedeemNo, 4, '0', STR_PAD_LEFT);

            $pawn_type = "Pawn";

            // ── Karatage pawning rate lookup ──────────────────────────
            // Ticket search uses Ticket_Number to find the actual receipt number
            $actualReceiptNo = $data->first()->Receipt_Number;

            $pawnDetails = TPawnDetails::where('Receipt_Number', $actualReceiptNo)
                ->where('BC', $branch_code)
                ->get();

            $karatages    = $pawnDetails->pluck('Karatage')->unique()->filter()->toArray();
            $karatageData = karatage::whereIn('descrption', $karatages)->get();
            // ─────────────────────────────────────────────────────────

            return view('redeemSearch')
                ->with('maxRedeem',      $maxRedeemNos)
                ->with('customerData',   $cus_data)
                ->with('receiptTypeData',$receipt_data)
                ->with('pawnType',       $pawn_type)
                ->with('receiptData',    $data)
                ->with('pawnDetails',    $pawnDetails)
                ->with('karatageData',   $karatageData);

        } else {
            return response()->json(['status' => 'not_found']);
        }
    }


    // ─────────────────────────────────────────────────────────────
    // Store Repawning Summary
    // ─────────────────────────────────────────────────────────────
public function StoreRepawningSum(Request $request)
{
    $request->validate([
        'receipt_number'        => 'required|string',
        'invoice_number'        => 'required|string',
        'ticket_number'         => 'required|string',
        'pawn_receipt_type'     => 'required|string',
        'redeem_date'           => 'required|date',
        'redeem_no'             => 'required|string',
        'sum_total_weight'      => 'required|numeric',
        'sum_pawn_weight'       => 'required|numeric',
        'original_pawn_amount'  => 'required|numeric',
    ]);

    DB::beginTransaction();

    try {
        $branch_code = auth()->user()->BC;
        $username    = auth()->user()->username;

        /* =========================
           CHECKBOX: document charges
           Only apply if checkbox ticked
        ==========================*/
        $documentCharges = $request->addServiceCharges == '1'
            ? ($request->document_charges ?? 0)
            : 0;

        /* =========================
           SAVE REPAWNING SUMMARY
        ==========================*/
        $NewRedeem = new TRepawningSum();
        $NewRedeem->Receipt_Number       = $request->receipt_number;
        $NewRedeem->Invoice_Number       = $request->invoice_number;
        $NewRedeem->Ticket_Number        = $request->ticket_number;
        $NewRedeem->Pawn_Receipt_Type    = $request->pawn_receipt_type;
        $NewRedeem->Redeem_Date          = $request->redeem_date;
        $NewRedeem->Redeem_Number        = $request->redeem_no;
        $NewRedeem->Total_Weight         = $request->sum_total_weight;
        $NewRedeem->Pawn_Weight          = $request->sum_pawn_weight;
        $NewRedeem->Original_Pawn_Amount = $request->original_pawn_amount;
        $NewRedeem->Payable_Pawn_Amount  = $request->original_pawn_amount;
        $NewRedeem->Paid_Interest        = $request->paid_interest ?? 0;
        $NewRedeem->Payable_Interest     = $request->payable_interest ?? 0;
        $NewRedeem->Stamp_Fee            = $request->stamp_fee ?? 0;
        $NewRedeem->Document_Charges     = $documentCharges;           // ✅ checkbox-aware
        $NewRedeem->Advance_Balance      = $request->advance_balance ?? 0;
        $NewRedeem->Discount             = $request->redeem_discount ?? 0;
        $NewRedeem->Payable_Total        = $request->payable_total;
        $NewRedeem->Redeem_total         = $request->redeem_total;
        $NewRedeem->validyed_type        = $request->validyed_type;
        $NewRedeem->OC                   = $username;
        $NewRedeem->BC                   = $branch_code;
        $NewRedeem->save();

        /* =========================
           CALCULATIONS
        ==========================*/
        $pawnSum = TPawnSum::where('Receipt_Number', $request->receipt_number)
            ->where('BC', $branch_code)
            ->firstOrFail();

        $totalPawnAmount = TPawnSum::where('Receipt_Number', $request->receipt_number)
            ->where('BC', $branch_code)
            ->sum('Pawn_Amount');

        $totalRepawnPayment = $totalPawnAmount
            + $request->payable_total
            + $request->paid_interest
            + $documentCharges;                                        // ✅ checkbox-aware

        /* =========================
           FINAL DATE
        ==========================*/
        $final_date = Carbon::parse($request->redeem_date)
            ->addMonths($request->validyed_type)
            ->toDateString();

        /* =========================
           PAWN TRANSACTION
        ==========================*/
        $TPawnTrans = new TPawnTrans();
        $TPawnTrans->Customer_NIC    = $request->Customer_NIC;
        $TPawnTrans->Customer_Name   = $request->Customer_Name;
        $TPawnTrans->code            = $request->receipt_number;
        $TPawnTrans->trans_no        = $request->redeem_no;
        $TPawnTrans->trans_type      = 'REPAWNING';
        $TPawnTrans->trans_amount    = $request->redeem_total;
        $TPawnTrans->dDate           = $request->redeem_date;
        $TPawnTrans->Dr_amount       = 0;
        $TPawnTrans->Cr_amount       = $request->payable_total;
        $TPawnTrans->Paided_Interest = $request->paid_interest;
        $TPawnTrans->Pawn_Amount     = $request->original_pawn_amount;
        $TPawnTrans->Extend_Date     = $final_date;
        $TPawnTrans->OC              = $username;
        $TPawnTrans->BC              = $branch_code;
        $TPawnTrans->save();

        /* =========================
           ACCOUNTING ENTRIES
        ==========================*/
        $cashAccountId           = 2;
        $pawnLoansAccountId      = 1;
        $interestIncomeAccountId = 3;
        $feeIncomeAccountId      = 4;

        $totalFees      = ($request->stamp_fee ?? 0) + $documentCharges; // ✅ checkbox-aware
        $extraCashGiven = $request->payable_total ?? 0;
        $interestPaid   = $request->paid_interest ?? 0;
        $netCashOut     = $extraCashGiven - $interestPaid - $totalFees;

        if ($extraCashGiven > 0) {
            DB::table('t_account_trans')->insert([
                'trans_date'   => $request->redeem_date,
               'voucher_no'   => 'REPAWNING-' . $request->invoice_number,
                'account_id'   => $cashAccountId,
                'related_id'   => $request->redeem_no,
                'related_type' => 'REPAWNING',
                'Invoice_no'   => $request->invoice_number,
                'dr'           => 0,
                'cr'           => $extraCashGiven,
                'description'  => 'Extra cash disbursed on repawning - Receipt #' . $request->invoice_number,
                'branch_code'  => $branch_code,
                'created_by'   => $username,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        /* =========================
           INTEREST RATE LOOKUP
        ==========================*/
        $rateRow = Recei_Add::where(function ($q) use ($totalRepawnPayment) {
            if ($totalRepawnPayment >= 100000) {
                $q->where('pawn_amount', '>=', 100000);
            } elseif ($totalRepawnPayment >= 50000) {
                $q->whereBetween('pawn_amount', [50000, 99999]);
            } else {
                $q->where('pawn_amount', '<', 50000);
            }
        })->first();

        $interestRateName = $rateRow->receiptname ?? null;
        $interestRate     = $rateRow->rate3 ?? 0;

        /* =========================
           UPDATE PAWN SUMMARY
        ==========================*/
        $updateData = [
            'RePawning_amount'   => $totalRepawnPayment,
            'Pawn_Amount'        => $totalRepawnPayment,
            'RePawn_get_amount'  => $request->payable_total,
            'RePawning_date'     => $request->redeem_date,
            'Pawn_Date'          => $request->redeem_date,
            'Middle_name'        => $interestRateName,
            'Interest_Rate'      => $interestRate,
            'Valid_Period'       => $request->validyed_type,
            'RePawning_interest' => $request->paid_interest,
            'Final_date'         => $final_date,
        ];

        if ($pawnSum->Receipt_Type !== 'SILVER') {
            $updateData['Receipt_Type'] = $interestRateName;
        }

        TPawnSum::where('Receipt_Number', $request->receipt_number)
            ->where('BC', $branch_code)
            ->update($updateData);

        /* =========================
           PDF GENERATION
        ==========================*/
        $pawnDetails = TPawnDetails::where('Receipt_Number', $request->receipt_number)
            ->where('BC', $branch_code)
            ->get();

        $karatages = $pawnDetails->pluck('Karatage')->unique()->toArray();

        $pdfData = [
            'repawnSumData'   => TRepawningSum::where('Receipt_Number', $request->receipt_number)
                                    ->where('BC', $branch_code)->get(),
            'pawnSumData'     => TPawnSum::where('Receipt_Number', $request->receipt_number)
                                    ->where('BC', $branch_code)->get(),
            'pawnDetailsData' => $pawnDetails,
            'companyData'     => Company::latest()->first(),
            'branchDetails'   => branchDel::where('bccode', $branch_code)->first(),
            'karatage_data'   => karatage::whereIn('descrption', $karatages)->get(),
            'T_Receipt_Type'  => Recei_Add::where('receiptname', $interestRateName)->first(),
        ];

        $pdf1     = PDF::loadView('reCustomerpawnReceiptPrint', $pdfData);
        $pdfPath1 = storage_path('../public/assets/pdf/Pawn_receipt_customer_' . $branch_code . '.pdf');
        $pdf1->save($pdfPath1);

        $pdf2     = PDF::loadView('reOfficepawnReceiptPrint', $pdfData);
        $pdfPath2 = storage_path('../public/assets/pdf/Pawn_receipt_office_' . $branch_code . '.pdf');
        $pdf2->save($pdfPath2);

        DB::commit();

        return back()
            ->with('done',     'Repawning completed successfully')
            ->with('pdfLink1', asset('public/assets/pdf/Pawn_receipt_customer_' . $branch_code . '.pdf'))
            ->with('pdfLink2', asset('public/assets/pdf/Pawn_receipt_office_'   . $branch_code . '.pdf'));

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}


    public function show($id)   { }
    public function edit($id)   { }
    public function update(Request $request, $id) { }
    public function destroy($id){ }
}
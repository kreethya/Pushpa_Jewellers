<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Http\Request;
use App\Models\itemCondition;
use App\Models\karatage;
use App\Models\Item;
use App\Models\Recei_Add;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Company;
use App\Models\TPawnDetails;
use App\Models\TPawnSum;
use App\Models\TRedeemSum;
use App\Models\TOpeningPawnSum;
use App\Models\TOpeningPawnDetails;
use App\Models\branchDel;
use App\Models\TForfeitSum;
use App\Models\MPawnfeedback;
use App\Models\TForfitDetails;
use App\Models\TItemMovement;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Support\Facades\DB;

class ForfeitReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('forfeitReceipt');
    }

public function store(Request $request)
{
    // ✅ Step 1: Validate input
    $request->validate([
        'receipt_number'       => 'required',
        'invoice_number'       => 'required',
        'pawn_receipt_type'    => 'required',
        'total_weight'         => 'required|numeric',
        'pawn_weight'          => 'required|numeric',
        'forfeit_date'         => 'required|date',
        'forfeit_no'           => 'required',
        'original_pawn_amount' => 'required|numeric',
        'stamp_fee'            => 'nullable|numeric',
        'document_charges'     => 'nullable|numeric',
        'advance_balance'      => 'nullable|numeric',
        'redeem_discount'      => 'nullable|numeric',
    ]);

    DB::beginTransaction();

    try {
        // ✅ Step 2: Save to TForfeitSum
        $NewForfeit = new TForfeitSum;
        $NewForfeit->Receipt_Number       = $request->receipt_number;
        $NewForfeit->Invoice_Number       = $request->invoice_number;
        $NewForfeit->Pawn_Receipt_Type    = $request->pawn_receipt_type;
        $NewForfeit->Total_Weight         = $request->total_weight;
        $NewForfeit->Pawn_Weight          = $request->pawn_weight;
        $NewForfeit->Forfeit_Date         = $request->forfeit_date;
        $NewForfeit->Forfeit_Number       = $request->forfeit_no;
        $NewForfeit->Original_Pawn_Amount = $request->original_pawn_amount;
        $NewForfeit->Payable_Pawn_Amount  = $request->original_pawn_amount;
        $NewForfeit->Paid_Interest        = $request->paid_interest;
        $NewForfeit->Payable_Interest     = $request->payable_interest;
        $NewForfeit->Stamp_Fee            = $request->stamp_fee;
        $NewForfeit->Document_Charges     = $request->document_charges;
        $NewForfeit->Advance_Balance      = $request->advance_balance;
        $NewForfeit->Discount             = $request->redeem_discount;
        $NewForfeit->Payable_Total        = $request->payable_total;
        $NewForfeit->OC                   = auth()->user()->username;
        $NewForfeit->BC                   = auth()->user()->BC;
        $NewForfeit->save();

        $receiptInput_no = $request->receipt_number;
        $branch_code     = auth()->user()->BC;

        // ✅ Step 3: Update isForfeit in TPawnSum & TPawnDetails
        TPawnSum::where('Receipt_Number', $receiptInput_no)
            ->where('BC', $branch_code)
            ->update(['isForfeit' => 1]);

        TPawnDetails::where('Receipt_Number', $receiptInput_no)
            ->where('BC', $branch_code)
            ->update(['isForfeit' => 1]);

        // ✅ Step 4: Fetch related data
        $T_sumdata     = TPawnSum::where('Receipt_Number', $receiptInput_no)->where('BC', $branch_code)->first();
        $T_detailsdata = TPawnDetails::where('Receipt_Number', $receiptInput_no)->where('BC', $branch_code)->get();
        $companyData   = Company::latest()->first();

        // ✅ Step 5: Save to TForfeitDetails and Item
        $latestCode = Item::max('Item_code');
        $nextCode   = $latestCode ? intval($latestCode) + 1 : 10000;

        foreach ($T_detailsdata as $detail) {
            // Save to TForfeitDetails
            $forfeitDetail = new TForfitDetails();
            $forfeitDetail->Receipt_Number = $detail->Receipt_Number;
            $forfeitDetail->Receipt_Type   = $request->pawn_receipt_type;
            $forfeitDetail->Category       = $detail->Category ?? null;
            $forfeitDetail->Articles       = $detail->Articles ?? null;
            $forfeitDetail->Condition      = $detail->Condition ?? null;
            $forfeitDetail->Karatage       = $detail->Karatage ?? null;
            $forfeitDetail->Weight         = $detail->Weight;
            $forfeitDetail->QTY            = $detail->QTY;
            $forfeitDetail->Value          = $detail->Value;
            $forfeitDetail->Date           = $request->forfeit_date;
            $forfeitDetail->IsRedeemed     = 0;
            $forfeitDetail->isForfeit      = 1;
            $forfeitDetail->Total_Weight   = $detail->Total_Weight ?? $detail->Weight;
            $forfeitDetail->OC             = auth()->user()->username;
            $forfeitDetail->BC             = auth()->user()->BC;
            $forfeitDetail->save();

            // ✅ Market rate calculation (assuming 8 = palan conversion, clarify if not)
            $marketRate = karatage::where('descrption', $detail->Karatage)->value('salepriceRate');
            $marketRateOneGram = $marketRate ? $marketRate / 8 : 0;
            $weight = $detail->Total_Weight ?? $detail->Weight;
            $marketRateprice = $marketRateOneGram * $weight;

            // ✅ Save to Item
            $Item_code = str_pad($nextCode, 5, '0', STR_PAD_LEFT);
            Item::create([
                'category'         => $detail->Category,
                'Receipt_Number'   => $detail->Receipt_Number,
                'Item_code'        => $Item_code,
                'Bar_code'         => $Item_code . '-' . \Carbon\Carbon::parse($request->forfeit_date)->format('Ymd') . '-' . $detail->Receipt_Number,
                'Item_description' => $detail->Articles,
                'Brand'            => $detail->Condition,
                'Color'            => null,
                'Make'             => $detail->Karatage,
                'image'            => null,
                'purchasePrice'    => $marketRate,
                'saleprice'        => $marketRateprice,
                'Total_Weight'     => $detail->Total_Weight ?? $detail->Weight,
                'Weight'           => $detail->Weight,
                'QTY'              => $detail->QTY,
                'Branch'           => auth()->user()->Branch,
                'BC'               => auth()->user()->BC,
            ]);

            // ✅ Item Movement
            $ItemMovementDetails = new TItemMovement;
            $ItemMovementDetails->trans_no   = $request->receipt_number;
            $ItemMovementDetails->dDate      = $request->forfeit_date;
            $ItemMovementDetails->Category   = $detail->Category;
            $ItemMovementDetails->trans_code = "FORFEIT";
            $ItemMovementDetails->item_code  = $Item_code;
            $ItemMovementDetails->qun_in     = $detail->QTY;
            $ItemMovementDetails->bc         = auth()->user()->BC;
            $ItemMovementDetails->save();

            $nextCode++;
        }

        // ✅ Step 6: Generate PDF
        $pdfPath = public_path('assets/pdf/RepawnReceiptPrint' . $branch_code . '.pdf');
        $pdf = PDF::loadView('ForfeitReceiptPrint', [
            'pawnSumData'     => $T_sumdata,
            'pawnDetailsData' => $T_detailsdata,
            'companyData'     => $companyData
        ]);
        $pdf->save($pdfPath);

           $pdfUrl = asset('public/assets/pdf/RepawnReceiptPrint'.$branch_code.'.pdf');

        DB::commit();

        return back()
            ->with('done', 'The Forfeit has been added successfully.')
            ->with('pdfLink', $pdfUrl);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Failed to store forfeit', [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString()
        ]);
        return back()->withErrors(['error' => 'Failed to save forfeit: ' . $e->getMessage()]);
    }
}




    public function create()
    {
       
    }


   //receipt Search function
   public function search(Request $request)
   {
       $receiptNo = $request->search_receipt_no;
       $branch_code = auth()->user()->BC;

       $dataTPawnSum = TPawnSum::where('Receipt_Number',$receiptNo)
                       ->where('IsRedeemed', 0)
                       ->where('isForfeit', 0)
                       ->where('BC',$branch_code)
                       ->get();




        $data = $dataTPawnSum;

       if($data->count()>0){

           $cus_nic = $data->first()->Customer_NIC;
           $cus_data = Customer::where('NIC', $cus_nic)->get();

           $receipt_typ = $data->first()->Receipt_Type;
           $receipt_data = Recei_Add::where('receiptname', $receipt_typ)->get();

           $maxRedeemNo = TForfeitSum::where('BC',$branch_code)
                            ->orderBy('Forfeit_Number', 'desc')
                            ->value('Forfeit_Number');

           $maxRedeemNos = str_pad($maxRedeemNo, 4, '0', STR_PAD_LEFT);

           $pawn_type = "Pawn";


        $MPawnfeedback = MPawnfeedback::where('Receipt_Number',$receiptNo)
                       ->where('BC',$branch_code)
                       ->get();


           return view('forfeitSearch')
           ->with("maxRedeem", $maxRedeemNos)
           ->with('customerData', $cus_data)
           ->with('receiptTypeData', $receipt_data)
           ->with('MPawnfeedback', $MPawnfeedback)
           ->with('pawnType', $pawn_type)
           ->with('receiptData', $data);
       }else{
           return response()->json([
               'status'=>'not_found'
           ]);
       }
   }

    //invoice Search function
    public function searchInvoice(Request $request)
    {
        $invoiceNo = $request->search_invoice_no;
        $branch_code = auth()->user()->BC;

        $TOpeningPawnSumdata = TOpeningPawnSum::where('Invoice_Number',$invoiceNo)
                                ->where('IsRedeemed', 0)
                                ->where('isForfeit', 0)
                                ->where('BC',$branch_code)
                                ->get();

        $data = $TOpeningPawnSumdata;

        if($data->count()>0){

            $cus_nic = $data->first()->Customer_NIC;
            $cus_data = Customer::where('NIC', $cus_nic)->get();

            $receipt_typ = $data->first()->Receipt_Type;
            $receipt_data = Recei_Add::where('receiptname', $receipt_typ)->get();

            $maxRedeemNo = TForfeitSum::where('BC',$branch_code)
                                    ->orderBy('Forfeit_Number', 'desc')
                                    ->value('Forfeit_Number');

            $maxRedeemNos = str_pad($maxRedeemNo, 4, '0', STR_PAD_LEFT);

            $pawn_type = "Opening_Pawn";

            return view('forfeitSearch')
            ->with("maxRedeem", $maxRedeemNos)
            ->with('customerData', $cus_data)
            ->with('receiptTypeData', $receipt_data)
            ->with('pawnType', $pawn_type)
            ->with('receiptData', $data);
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ForfeitReceiptList(Request $request)
    {
        $currentDateTime = now();
        $branch_code = auth()->user()->BC;

        $oldPawns = TPawnSum::whereDate('Final_date', '<', $currentDateTime)
                    ->where('IsRedeemed', 0)
                    ->where('BC', $branch_code)
                    ->get(); // <-- Fixed here

        return view('forfeitReceipt_List')
            ->with("recipts", $oldPawns);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
        public function getReceiptDetails(Request $request)
        {
            $receiptNumber = $request->receipt_number;

            $feedbacks = MPawnfeedback::where('Receipt_Number', $receiptNumber)->get();

            if ($feedbacks->isNotEmpty()) {
                return view('partials.receipt-details', compact('feedbacks'));
            } else {
                return "<p>No receipt feedback found.</p>";
            }
        }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
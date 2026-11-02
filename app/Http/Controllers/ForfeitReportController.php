<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TPawnSum;
use App\Models\TOpeningPawnSum;
use App\Models\TForfitDetails;
use App\Models\Item;
use App\Models\GetItemData;
use Carbon\Carbon;


class ForfeitReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
 public function index(Request $request)
    {
    // Get search criteria from the form input
    $fromDate  = $request->input('from_date');
    $toDate = $request->input('to_date');
    $branch_code =auth()->user()->BC;

    $query = TPawnSum::whereBetween('Receipt_Date', [$fromDate, $toDate])
            ->where('IsRedeemed', 0)
             ->where('BC', $branch_code)
            ->where('isForfeit', 1)
            ->get();

    if ($fromDate && $toDate) {
        $dataTPawnSum = TPawnSum::whereBetween('Receipt_Date', [$fromDate, $toDate])
                        ->where('IsRedeemed', 0)
                        ->where('BC', $branch_code)
                        ->where('isForfeit', 1)
                        ->get();

        $dataTOpeningPawnSum = TOpeningPawnSum::whereBetween('Receipt_Date', [$fromDate, $toDate])
                                ->where('IsRedeemed', 0)
                                ->where('isForfeit', 1)
                                  ->where('BC', $branch_code)
                                ->get();

        $data = $dataTPawnSum->concat($dataTOpeningPawnSum);


        $total = $data->sum('Amount');
        $totalAmount = number_format($total, 2);
        $int =  $data->sum('Interest');
        $interest = number_format($int, 2);

        return view('forfeitReport')
         ->with("recipts", $data)
        -> with("recipts", $data)
        -> with("amount", $totalAmount)
        -> with("Interest", $interest);
    }

    // Construct a query to filter records based on search criteria
    $query = TPawnSum::query();

    $total = $query->sum('Amount');
    $totalAmount = number_format($total, 2);
    $int =  $query->sum('Interest');
    $interest = number_format($int, 2);
    $TotalPawn = TPawnSum::count();

    return view('forfeitReport')
    -> with("recipts", $query)
    -> with("recipts", $query)
    -> with("amount", $totalAmount)
    -> with("Interest", $interest);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
public function ForfeitArticleList(Request $request)
{
    $branch_code = auth()->user()->BC;

    // Start query: filter by branch and not moved to items yet
    $query = Item::where('BC', $branch_code)
             ->where('SaleIsItem', 0)
             ->where('IntoItem', 0);


    // Apply date range filter if provided
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('Date', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ]);
    }

    // Execute query and get results
    $TForfitDetails = $query->get();

    // Pass data to view
    return view('forfeit_article_receipt')->with('recipts', $TForfitDetails);
}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function storeFromForfeit(Request $request)
{
    $branch_code = auth()->user()->BC;
    $selectedIds = $request->input('selected_ids', []);

    if (empty($selectedIds)) {
        return back()->with('error', 'No records selected.');
    }

    $forfeitItems = Item::whereIn('id', $selectedIds)
        ->where('BC', $branch_code)
        ->get();

    // Update the original forfeit items
    Item::whereIn('id', $selectedIds)
        ->where('BC', $branch_code)
        ->update(['IntoItem' => 1]);

    // Get the max existing item_code
    $latestCode = GetItemData::max('Item_code');
    $nextCode = $latestCode ? intval($latestCode) + 1 : 10000;

    foreach ($forfeitItems as $detail) {
        GetItemData::create([
                        'Receipt_Number'   => $detail->Receipt_Number,
                        'category'         => $detail->category,
                        'Item_code'        => str_pad($nextCode, 5, '0', STR_PAD_LEFT),
                        'Bar_code'         => str_pad($nextCode, 5, '0', STR_PAD_LEFT) . '/' . \Carbon\Carbon::parse($request->forfeit_date)->format('Ymd') . '/' . $detail->Receipt_Number,
                        'Item_description' => $detail->Item_description,
                        'Brand'            => $detail->Brand,
                        'Karatage'         => $detail->Make,
                        'purchasePrice'    => $detail->purchasePrice,
                        'saleprice'        => $detail->saleprice,
                        'Total_Weight'     => $detail->Total_Weight ?? $detail->Weight,
                        'Weight'           => $detail->Weight,
                        'QTY'              => $detail->QTY,
                        'Branch'           => auth()->user()->Branch,
                        'BC'               => auth()->user()->BC,
        ]);

        $nextCode++;
    }

    return redirect()->back()->with('success', 'Selected forfeit articles saved to items table.');
}



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
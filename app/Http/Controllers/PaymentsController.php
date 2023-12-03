<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\transactionHistory;
use App\Models\web_loan_application;
use RealRashid\SweetAlert\Facades\Alert;



class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('Payments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        //
        $request->validate([
            'loan_number' => 'required|exists:web_loan_applications',
            'loan_amount' => 'required|numeric',
            'transaction_id' => 'required|string|unique:transaction_histories',
            'payment_method' => 'required|string',
            'reference_number' => 'required|string',
            
        ]);

        // The Loan Number exists and everything is fine upto here Now
        // Check if the Loan Number is active|pending|completed
       $loan_status = web_loan_application::where('loan_number',"=",$request->loan_number)->where('approved',"=",1)->exists();
      if($loan_status){
     
      // Create Transactions
      transactionHistory::create([    
        'loan_number' => $request->loan_number,
        'loan_amount' => $request->loan_amount,
        'payment_method' => $request->payment_method,
        'transaction_id' => $request->transaction_id,
        'reference_number' => $request->reference_number,
        'user_id' => auth()->user()->employee_id,
    ]);

      
     // Update or create Balance Due
     $total_paid = transactionHistory::where('loan_number',"=",$request->loan_number)->sum('loan_amount');
     $loan_got = web_loan_application::where('loan_number',"=",$request->loan_number)->sum('loan_amount'); 
     $balance_due = ($loan_got - $total_paid);


    $update_balance = transactionHistory::where('loan_number',"=",$request->loan_number)->latest()->first();   
    $update_balance->balance_due = $balance_due; 
    $update_balance->save(); 

// Check if the Loan has been completed 
if($balance_due <= 0){
$loan_done = web_loan_application::where('loan_number',"=",$request->loan_number)->first();
$loan_done->approved = 4;
$loan_done->save();
toast('This Loan Re-Payment has been paid and completed successfully!','success');
return redirect()->back();
}

else{
    toast('A partial repayment of the Loan has been made successfully!','success');
    return redirect()->back();   
}

}
    
    else{
        toast('This Loan has either been paid in Full, denied or is pending approval!','error');
        return redirect()->back();    
    }
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

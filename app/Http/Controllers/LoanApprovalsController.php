<?php

namespace App\Http\Controllers;

use App\Models\web_loan_application;
use App\Models\reg_employee_mst;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Approvals;
use App\Notifications\SignatureNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class LoanApprovalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = web_loan_application::where('approved', "=", 0)->exists();
        if($status){
        return view('LoanApprovals.index',[
            'loan_applications' => web_loan_application::where('approved', "=", 0)->first(),
            'total_to_approve' => web_loan_application::where('approved', "=", 0)->count()
        ]);
    }
    else{
        toast('You have no new Loans to review!','success');
        return redirect()->route('admindashboard');   
    }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $request->validate([
            'loan_number' => 'required|numeric',
            'answer1' => 'required|string',
            'answer2' => 'required|string',
            'answer3' => 'required|string',
            'answer4' => 'required|string',
            'answer5' => 'required|string',
            'answer6' => 'required|string',
            'answer7' => 'required|string',
            'answer8' => 'required|string',
            'answer9' => 'required|string',
            'answer10' => 'required|string',
            'answer11' => 'required|string',
            'loan_officer_decision' => 'required|string',
            'loan_officer_comments' => 'required|string',
            
        ]);
        //
        $add_user = new Approvals; 
        $add_user -> loan_number = $request->loan_number; 
        $add_user -> answer1 = $request->answer1; 
        $add_user -> answer2 = $request->answer2; 
        $add_user -> answer3 = $request->answer3; 
        $add_user -> answer4 = $request->answer4; 
        $add_user -> answer5 = $request->answer5; 
        $add_user -> answer6 = $request->answer6; 
        $add_user -> answer7 = $request->answer7; 
        $add_user -> answer8 = $request->answer8; 
        $add_user -> answer9 = $request->answer9; 
        $add_user -> answer10 = $request->answer10; 
        $add_user -> answer11 = $request->answer11; 
        $add_user -> loan_officer_decision = $request->loan_officer_decision == 'yes' ? 2 : 3;  //2 For Yes and 3 For no
        $add_user -> loan_officer_comments = $request->loan_officer_comments; 
        $add_user -> user_id = auth()->user()->employee_id; 
        $add_user -> save(); 


        // Update Loan Status Based on the Loan Officers Analysis
        $loan_status = web_loan_application::where('loan_number',"=",$request->loan_number)->first();
        $loan_status->approved = $request->loan_officer_decision == 'yes' ? 5 : 6; // Status For Loan Approval/Denie From the Loan Officer
        $loan_status->save();
  
        
        $applicant = reg_employee_mst::find($loan_status->employee_id);
        if (!$applicant->hasBeenSigned()) {   


    ## Send Email Notification to the user together with the loan number
    ## For the purpose of Loan Signature Verification
    $loan_number =  $request->loan_number;
    $email_notification = reg_employee_mst::find($loan_status->employee_id);
    $loan_applicant_name = $email_notification->firstname. ' '.$email_notification->lastname;
    $email_notification->notify(new SignatureNotification($loan_number,$loan_applicant_name));

   
        }

        toast('The Loan Analysis has been submitted for verification to the CFO Succesfully!','success');
        return redirect()->route('loan_approvals.index');    

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
    public function update(Request $request, $loan_approval)
    {
        
        //
      $request->validate([
        'loan_number' => 'required|numeric',
        'cfo_decision' => 'required|string',
        'cfo_comments' => 'required|string'
      ]);  
       
      $loan_data = Approvals::find($loan_approval);
      $loan_data->update([
        'cfo_decision' => $request->cfo_decision == 'yes' ? 1 : 4, //1 For Yes and 4 for No
        'cfo_comments' => $request->cfo_comments
      ]);

 // Update Loan Status Based on the Chief Financial officers Analysis
 $loan_status = web_loan_application::where('loan_number',"=",$request->loan_number)->first();
 $loan_status->approved = $request->cfo_decision == 'yes' ? 7 : 8; // Status For Loan Approval/Denie From the CFO
 $loan_status->save();

 toast('The Loan Analysis has been submitted for verification to the ADMIN Succesfully!','success');
 return redirect()->route('review'); 

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

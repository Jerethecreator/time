<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\web_loan_application;
use App\Models\transactionHistory;
use App\Models\reg_employee_mst;
use Carbon\Carbon;
use DataTables;
class TodaysPaymentsController extends Controller
{
    //


    public function index(){
    
        return view('TodaysPayments.index');
               } 
    
    
    
    
    
    /**
         * Here follows the actions to be peformed by the Admin
         * Check Messages sent by users from the main website 
         * 
         * 
         * @param  \App\Http\Requests\Auth\LoginRequest  $request
         * @return \Illuminate\Http\RedirectResponse
         */
    
         public function todays_payments(){

            // Get today's date
    $today = Carbon::today();

    // Calculate the date 30 days ago
    $thirtyDaysAgo = $today->subDays(30);





            $data = transactionHistory::where('balance_due',">",0 )->where('updated_at', '=', $thirtyDaysAgo)->get();
            
            //



                return Datatables::of($data)
                    ->addIndexColumn()  
                    ->addColumn('name', function($data){
                        $loan = web_loan_application::where('loan_number',"=",$data->loan_number)->first();
                        $user = reg_employee_mst::find($loan->employee_id);
                        return $user->firstname. ' '.$user->lastname;
                    })  
                    ->addColumn('phone', function($data){
                        $loan = web_loan_application::where('loan_number',"=",$data->loan_number)->first();
                        $user = reg_employee_mst::find($loan->employee_id);
                        return $user->phone;
                    })  
                    ->addColumn('email', function($data){
                        $loan = web_loan_application::where('loan_number',"=",$data->loan_number)->first();
                        $user = reg_employee_mst::find($loan->employee_id);
                        return $user->email;
                    })  
                    ->addColumn('last_payment_date', function($data){
                       return date('j, F-Y',strtotime($data->updated_at));
                    })   
                  
                     ->addColumn('balance_due', function($data){
                        $loan = web_loan_application::where('loan_number',"=",$data->loan_number)->first();
                        return $loan->emi;
                     })   


                    
                     
                    ->rawColumns(['name','phone','email','last_payment_date','balance_due'])
                    ->make(true);
        }
        
    
    
}

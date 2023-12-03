<?php

namespace App\Http\Controllers;

use App\Models\web_loan_application;
use Illuminate\Http\Request;
use DataTables;
use App\Models\reg_employee_mst;

class DeniedLoansController extends Controller
{
           /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


public function denied_loans(Request $request){
    
    // if ($request->ajax()) {
        
         $data = web_loan_application::where('approved',"=",3)->get();
         return Datatables::of($data)
             ->addIndexColumn()  
             ->addColumn('loan_number', function($data){
                return $data->loan_number;
            })  
             ->addColumn('loan_type', function($data){
                if($data->loan_type == 1){
                    return "PAYROLL BASED LOAN";
                }
                elseif($data->loan_type == 2){
                    return "AUTO LOAN";
                }
                else{
                    return "PRIVATE SECTOR LOAN";    
                }
             })   
             ->addColumn('mannumber', function($data){
                $employee = reg_employee_mst::find($data->employee_id);
                if($employee){
                   return $employee->mannumber;
                }
                else{
                   return 'N/A';
                }
                
             })   
             ->addColumn('company_id', function($data){
                
                 return $data->company_id;
             })   
             ->addColumn('duration', function($data){
                return $data->months;
             })  
             ->addColumn('loan_amount', function($data){
                 return $data->loan_amount;
             })  
            
             ->addColumn('payment_mode', function($data){
                 return $data->payment_mode;
             })   
             ->addColumn('mobile_money_number', function($data){
                 return $data->mobile_money_number;
             })  
             ->addColumn('mobile_money_name', function($data){
                 return $data->mobile_monney_name;
             })  
            
            
            
             ->addColumn('created_at', function($data){
                 return date('d,F-Y',strtotime($data->created_at));
             })  
             
             ->rawColumns(['loan_number','loan_type','mannumber','company_id','duration','loan_amount','payment_mode','mobile_money_number','mobile_money_name','created_at'])
             ->make(true);
     //}
 
 
 }
 
 
 
     public function index()
     {
         //
     return view('DeniedLoans.denied_loans');    
     }
 
     
    }
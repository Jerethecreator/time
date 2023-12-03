<?php

namespace App\Http\Controllers;

use App\Models\web_loan_application;
use Illuminate\Http\Request;
use DataTables;
use App\Models\reg_employee_mst;

class LoanHistoryController extends Controller
{
           /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


public function loan_history(Request $request){
    
    // if ($request->ajax()) {
        
         $data = web_loan_application::where('employee_id',"=",auth()->user()->employee_id)->get();
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
             ->addColumn('status', function($data){
                if($data->approved == 0){
                    return "Processing";
                }
                elseif($data->loan_type == 1){
                    return "APPROVED";
                }
                else{
                    return "processing";    
                }
             })   
             
             ->addColumn('duration', function($data){
                return $data->months;
             })  
             ->addColumn('amount', function($data){
                return $data->amount;
            }) 
             ->addColumn('loan_amount', function($data){
                 return $data->loan_amount;
             })
             ->addColumn('emi', function($data){
                return $data->emi;
            }) 
            ->addColumn('created_at', function($data){
                return date('j, F Y',strtotime($data->created_at));
            })  
            
             
           
             ->rawColumns(['loan_number','loan_type','status','duration','amount','loan_amount','emi','created_at'])
             ->make(true);
     //}
 
 
 }
 
 
 
     public function index()
     {
       
         //
     return view('LoanHistory.index');    
     }
 
     
    }
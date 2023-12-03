<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\reg_employee_mst;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Gate;

use DataTables;
use DateTime;
class RegisteredUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


public function all_users(Request $request){
    
   // if ($request->ajax()) {
       
        $data = reg_employee_mst::get();
        return Datatables::of($data)
            ->addIndexColumn()  
            ->addColumn('first_name', function($data){
                return $data->firstname;
            })   
            ->addColumn('last_name', function($data){
                return $data->lastname;
            })   
            ->addColumn('mannumber', function($data){
               
                return $data->mannumber;
            })   
            ->addColumn('nrc', function($data){
               return $data->nrc;
            })  
            ->addColumn('dob', function($data){
                $date = DateTime::createFromFormat('d/m/Y', $data->dob);
                $formatted_date = date("j, F Y", strtotime($date->format('Y-m-d')));
                return $formatted_date;

            })  
            ->addColumn('gender', function($data){
                return $data->gender;
            })  
            ->addColumn('marital_status', function($data){
                return $data->marital_status;
            })  
            ->addColumn('email', function($data){
                return $data->email;
            })   
            ->addColumn('phone', function($data){
                return $data->phone;
            })  
            ->addColumn('net_salary', function($data){
                return $data->net_salary;
            })  
            ->addColumn('company_id', function($data){
                return $data->company_id;
            })  
            ->addColumn('bank_id', function($data){
                return $data->bank_id;
            })  
            ->addColumn('bank_branch_id', function($data){
                return $data->bank_branch_id;
            })  
            ->addColumn('bank_account_number', function($data){
                return $data->bank_account_no;
            })  
            ->addColumn('bank_account_name', function($data){
                return $data->bank_account_name;
            })  
            ->addColumn('created_at', function($data){
                return date('d,F-Y',strtotime($data->created_dt));
            })  
            
            ->addColumn('action', function ($data) {
                $btn = "<div class='table-actions'>
                    <a href='" . route('users.show', ['user' => $data->employee_id]) . "' class='show-employee cursure-pointer'><i class='fas fa-eye text-primary'></i></a>";
            
                if (Gate::allows('can-edit-registered-users')) {
                    $btn .= "<a href='" . route('users.edit', ['user' => $data->employee_id]) . "'><i class='fas fa-pencil text-dark'></i></a>";
                }
                else{
                    $btn .= "<div class='no-permission'><i class='fas fa-pencil text-dark'></i></div>";    
                }
            
                if (Gate::allows('can-delete-registered-users')) {
                    $btn .= "<a data-bs-toggle='modal' data-bs-target='#delete-modal' href='" . route('users.destroy', ['user' => $data->employee_id]) . "' class='delete-link'>&#x1F5D1;</a>";
                }
                else{
                    
                    $btn .= "<div class='no-permission'>&#x1F5D1;</div>";  
                }
            
                $btn .= "</div>";
            
                return $btn;
            
        }) 
            
            ->rawColumns(['first_name','last_name','mannumber','nrc','dob','gender','marital_status','email','phone','net_salary','company_id','bank_id','bank_branch_id','bank_account_number','bank_account_name','created_at','action'])
            ->make(true);
    //}


}



    public function index()
    {
        //
    $employees = reg_employee_mst::get();
    return view('registered_users.users');    
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    
    {
        //
        return view('registered_users.show',[
       'employee' => reg_employee_mst::find($user)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user)
    {
        //
       return view('registered_users.edit',[
        'employee' => reg_employee_mst::find($user)
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    
    {
       
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'mannumber' => 'nullable|string',
            'gender' => 'required|string',
            'marital_status' => 'required|string',
            'phone' => 'required|string',
            'nrc' => 'required|string',
            'dob' => 'required|before:2005-01-01',
            'address' => 'required|string',
            'email' => 'required|email',
            'position' => 'nullable|string',
            'net_salary' => 'required|numeric',
            'bank_id' => 'required|string',
            'bank_branch' => 'required|string',
            'bank_account_number' => 'required|string',
            'bank_account_name' => 'required|string',
            'mobile_money_number' => 'required|string',
            'mobile_money_name' => 'required|string',
            
        ]);
        //
       
    $personalDetails=reg_employee_mst::find($user);
    $personalDetails->firstname=$request->input('firstname');
    $personalDetails->lastname=$request->input('lastname');
    $personalDetails->mannumber=$request->input('mannumber');
    $personalDetails->dob=$request->input('dob');
    $personalDetails->gender=$request->input('gender');
    $personalDetails->nrc=$request->input('nrc');
    $personalDetails->marital_status=$request->input('marital_status');
    $personalDetails->phone=$request->input('phone');
    $personalDetails->address=$request->input('address');
    $personalDetails->email=$request->input('email');
    $personalDetails->position=$request->input('position');
    $personalDetails->net_salary=$request->input('net_salary');
    $personalDetails->bank_id=$request->input('bank_id');
    $personalDetails->bank_branch_id=$request->input('bank_branch');
    $personalDetails->bank_account_no=$request->input('bank_account_number');
    $personalDetails->bank_account_name=$request->input('bank_account_name');
    $personalDetails->mobile_money_no=$request->input('mobile_money_number');
    $personalDetails->mobile_money_name=$request->input('mobile_money_name');
    $personalDetails->save();


    toast($request->input('firstname').'s Personal Details updated successfully!','success');
     return redirect()->back();
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        $user = reg_employee_mst::find($user);
        $user->delete();
        toast('User has been trashed  successfully!','success');
        return redirect()->back();
        //
    }
}

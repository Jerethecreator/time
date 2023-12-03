<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{SettlementForms,web_loan_application};
use RealRashid\SweetAlert\Facades\Alert;
class UploadSettlementsFormsController extends Controller
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
        return view('Settlements.create');
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
            'loan_number' => 'required|exists:web_loan_applications',
            'settlement_file' => 'required|mimes:pdf|max:10240' //Max:10mb           
            
        ]);
$user_id = web_loan_application::where('loan_number',"=",$request->loan_number)->first();
$employee_id = '';
if($user_id){
    $employee_id = $user_id->employee_id;
}        SettlementForms::updateOrCreate([
    
            'loan_number'   => $request->loan_number
               
        ], [
          'settlement_file' => $request->file('settlement_file')->store('settlements','settlements'), 
          'user_id' => $employee_id
        ]);       

        toast('Settlement Uploaded Successfully!','success');
        return redirect()->back();   

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

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

class RemindersController extends Controller
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
        return view('Reminders.create');
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
'numbers' => 'required|array|max:4',
'message' => 'required|string|max:120'
        ]);
        
        $cat_time = Carbon::now()->addHours(2); 
        $scheduled_time = date('Y-m-d H:i', strtotime($cat_time->addMinutes(1)));
       
        
       $response = Http::withToken(env('BULK_SMS_TOKEN'))->post(env('BULK_SMS_BASE_URI').'/sms/send', [
            "sender_id" => env('BULK_SMS_SENDER_ID'),
            "type" => "plain",
            "schedule_time" => $scheduled_time,
            "message" => $request->message,
            "recipient" => $request->numbers
            
        ]); 


        toast('Message(s) Sent Successfully!','success');
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

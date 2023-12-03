<?php
namespace App\Http\Controllers;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Http\Request;
use App\Models\ref_payment_mode;
use App\Models\ref_town_mst;
use App\Models\reg_employee_attachment;
use App\Models\reg_employee_mst;
use App\Models\transactionHistory;
use App\Models\emailsubscription;
use App\Models\SettlementForms;
use App\Models\message;
use App\Models\website_profile;
use File;
use App\Models\web_article;
use App\Models\web_loan_application;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Session;
use App\Notifications\approve;
use App\Notifications\denie;
use App\Models\Approvals;
use Illuminate\Support\Facades\Notification;
use DataTables;
class loanapp extends Controller
{


/**
     * Loan Application Form Blade View.
     * 
     * 
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    
public function loanapplication($id){
    $profileclient=reg_employee_mst::find(decrypt($id));
    $employeeData=reg_employee_mst::where('email',"=",$profileclient->email)->firstOrFail();
    $refPaymentMode=ref_payment_mode::all();
    return view('loanapplication', compact('profileclient','employeeData','refPaymentMode'));
  
} 

/**
     * Loan Application Form to be filled in by the client who wants to apply for a loan.
     * 
     * 
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */

public function web_loan_application(Request $request,$id){
   

    $validate=validator::make($request->all(),[
        'loan_type'=>['required','string'],
        'employee_id'=>['required','numeric'],
        'tenure_months'=>['required','numeric'],         
        'loan_amt'=>['required','numeric'],  
        'total_repayments_amt'=>['required','numeric'],  
        'emi'=>['required','numeric'],  
        'payment_mode_id'=>['required','string'],   
        'payslip1'=>['nullable','mimes:pdf'],   
        'payslip2'=>['nullable','mimes:pdf'],   
        'asset_name'=>['nullable','string'],   
        'asset_location'=>['nullable','string'],   
        'asset_estimate'=>['nullable','numeric'],   
        'bankstatement'=>['required','mimes:pdf'],  
         
    ]);
   if ($validate->fails()){
    return Redirect::back()->withErrors($validate)->withInput();
   }

   

/**
 * Generate the Loan Number in the system and save it in the database
 * If the Loan Number exists in the database tell the user to resubmit
 * 
**/
   $number = random_int(1000000000, 9999999999);
   $loannumber = decrypt($id).$number;

   if(web_loan_application::where('loan_number',"=",$loannumber)->exists()){


    toast('Whoops something went wrong, try again!','error');
    return redirect('dashboard');
 
        
  

} 


/**
 * Check if the user has already submitted a loan. 
 * If Yes Denie application and redirect back with Msg.
 * 
**/
   
   elseif (web_loan_application::where('employee_id',"=",$request->employee_id)->where('approved',"=",0)->exists()){
    toast('It seems You have a pending Loan. First settle this Loan then you can apply later!','error');
    return redirect('dashboard');
    
    } 

    
/**
 * Proceed with Loan submission if the above two conditions  
 * are not met and everything is ok.
 * 
**/



else{
   
    $loan_application= new web_loan_application;
    $loan_application->loan_type = $request->loan_type;
    $loan_application->employee_id = $request->employee_id;
    $loan_application->months = $request->tenure_months;
    $loan_application->amount = $request->loan_amt;
    $loan_application->loan_amount = $request->total_repayments_amt;
    $loan_application->emi = $request->emi;
    $loan_application->payment_mode = $request->payment_mode_id;
    $loan_application->loan_number = $loannumber;
    $loan_application->mobile_money_number = auth()->user()->mobile_money_no;
    $loan_application->mobile_monney_name = auth()->user()->mobile_money_name;



    // If the Loan is For Civil Servants Or Private Sector
if($request->loan_type == 1 || $request->loan_type == 3){
    $loan_application->payslip1 = $request->payslip1->store('payslips');
    $loan_application->payslip2 = $request->payslip2->store('payslips');
    $loan_application->company_id = auth()->user()->company_id;
}
// If the Loan is For Auto Loans
else{
    $loan_application->asset_name = $request->asset_name;
    $loan_application->asset_estimate = $request->asset_estimate;
    $loan_application->asset_location = $request->asset_location;
    $loan_application->whitebook = $request->whitebook->store('whitebooks');
    $loan_application->front_image = $request->front_image->store('front_images');
    $loan_application->back_image = $request->back_image->store('back_images');
    $loan_application->left_image = $request->left_image->store('left_images');
    $loan_application->right_image = $request->right_image->store('right_images');
    $loan_application->chassis_number = $request->chassis_number->store('chassis_number_images');
    $loan_application->mileage = $request->mileage->store('mileage_images');
   
}
   
   
    $loan_application->bank_statement = $request->bankstatement->store('bank_statement');
    $loan_application->approved = 11; //Pending verification from Applicant
    $loan_application->due_date = Carbon::now()->addMonths($request->tenure_months)->format('d-m-Y');
    $loan_application->save();

if($request->loan_type == 1){
    Alert::success('Loan Summary', 'Here is a summary of your Civil Servant loan:');
    return view('LoanSummary.payroll',[
        'loan_number' => $loannumber
    ]);
}

elseif($request->loan_type == 2){
    Alert::success('Loan Summary', 'Here is a summary of your Auto Based loan:');
    return view('LoanSummary.auto',[
        'loan_number' => $loannumber
    ]);
}

else{
    Alert::success('Loan Summary', 'Here is a summary of your Private Sector loan:');
    return view('LoanSummary.private',[
        'loan_number' => $loannumber
    ]); 
}
   
 
    
}
}


public function export_borrower(){
    return view('Exports.borrowers');
   
}



public function terms_payroll($loan_number){
    return view('LoanTerms.client_payroll',[
        'loan_number' => decrypt($loan_number)
    ]);
   
}


public function terms_auto($loan_number){
    return view('LoanTerms.client_auto',[
        'loan_number' => decrypt($loan_number)
    ]);
   
}

public function terms_private($loan_number){
    return view('LoanTerms.client_private',[
        'loan_number' => decrypt($loan_number)
    ]);
   
}



public function verify_loan_application(Request $request){
    $request->validate([
        
        'loan_number' => 'required|numeric'
    ]);


    $loan_application = web_loan_application::where('loan_number',"=",$request->loan_number)->first();
    $loan_application->approved = 0;
    $loan_application->save();

    toast('Loan Application submitted successfully!','success');
    return redirect('dashboard');



}






/**
 * Check payments of the user from Loan settlements. 
 * Once a user has settled payments on the system
 * Using the Local Mobile Money integrated in our system
 * The system Local Mobile Money will send a request
 * To our callbackUrl and we will keep that data in 
 * the transactions_histories table 
 * 
 * 
**/


public function transaction_histories(Request $request){
    
   // $checkpayments=transactionHistory::where('transaction_id',"=",decrypt($id))->paginate(2);
        
         $data = transactionHistory::where('reference_number',"=",auth()->user()->nrc)->get();
         return Datatables::of($data)
             ->addIndexColumn()  
             ->addColumn('loan_number', function($data){
                return $data->loan_number;
            })  
             ->addColumn('loan_amount', function($data){
                 return $data->loan_amount;
             })   
             ->addColumn('message', function($data){
                return $data->message;
             })   
             ->addColumn('transaction_id', function($data){
                
                 return $data->transaction_id;
             })   
             ->addColumn('balance_due', function($data){
                return $data->balance_due;
             })  
             ->addColumn('payment_method', function($data){
                 return $data->payment_method;
             })  
            
                         ->addColumn('created_at', function($data){
                 return date('j,F-Y',strtotime($data->created_at));
             })  
             
             ->rawColumns(['loan_number','loan_amount','message','transaction_id','balance_due','payment_method','created_at'])
             ->make(true);
     //}
 
 
 }







public function checkpaymentstatus(Request $request){
    
    
    
    return view('TransactionHistories.checkpayments');
  
}  






/**
     * Here follows the actions to be peformed by the Admin
     * Submiting of new articles on the Website's Landing Page 
     * 
     * 
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */

 

public function admin_articles(Request $request){
    $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'body' => ['required', 'string', 'max:5000'],
        'image' => ['required', 'file', 'mimes:jpg,png,gif,jpeg'],
       ]);

       $article = new web_article;
       $article->title = $request->title;
       $article->body = strip_tags($request->body);
       $article->cover_page = $request->image->storeAs('articles_images', $request->image->getClientOriginalName());
       $article->created_by =  Auth::user()->firstname. " " . Auth::user()->lastname;
       $article->save();
      
       toast('Published Successfully!','success');
       return redirect()->back();
    
}  







## Showing the articles to the viewers  who visits the Landing Page 

public function articles_view(Request $request){
   
       $articles =web_article::paginate(1);
       return view('news',compact('articles'));
      
}  








/**
     * Here follows the actions to be peformed by the Admin
     * Check Email Subscribers  
     * 
     * 
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */


public function all_emails(){
    $data = emailsubscription::get();
    
        return Datatables::of($data)
            ->addIndexColumn()  
            ->addColumn('email', function($data){
                return $data->email;
            })   
            ->addColumn('created_at', function($data){
                return date('d,F-Y',strtotime($data->created_at));
            })   

             
            ->rawColumns(['email','created_at','action'])
            ->make(true);
}






public function emailsub(){
    
    return view('Emails.index'); 
  
} 





/**
     * Here follows the actions to be peformed by the Admin
     * Check Messages sent by users from the main website 
     * 
     * 
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */

     public function all_messages(){
        $data = message::get();
        
            return Datatables::of($data)
                ->addIndexColumn()  
                ->addColumn('name', function($data){
                    return $data->name;
                })  
                ->addColumn('email', function($data){
                    return $data->email;
                })  
                ->addColumn('subject', function($data){
                    return $data->subject;
                })   
                ->addColumn('message', function($data){
                    return $data->message;
                })  
                ->addColumn('created_at', function($data){
                    return date('d,F-Y',strtotime($data->created_at));
                })   
    
                 
                ->rawColumns(['name','email','subject','message','created_at'])
                ->make(true);
    }
    



    public function add_message(Request $request){
 
 $request->validate([
    'name' => 'required|string',
    'email' => 'required|email',
    'subject' => 'required|string',
    'message' => 'required|string'
 ]);

 message::create($request->all());


 toast('We have recieved your Message. We will get in touch as soon as possible!','success');
 return redirect('/');   
       
       
      
    } 





public function message(){
 
    return view('Messages.index');
   
  
} 













/**
     * Here follows the actions to be peformed by the Admin 
     * Loan Analysis  
     * 
     * 
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
 
     
     public function settlements_forms_downloads(){
        $data = SettlementForms::where('user_id', "=", auth()->user()->employee_id)->get();
        return Datatables::of($data)
            ->addIndexColumn()  
            ->addColumn('loan_number', function($data){
                return $data->loan_number;
            })   
            ->addColumn('settlement_file', function($data){
                $btn = '<div class="table-actions">
                    <a href="'.asset('settlements_files/'.$data->settlement_file).'" class="show-employee cursure-pointer">Download</a>
                </div>';
                return $btn;
            })   
            ->addColumn('created_at', function($data){
                return date('j, F-Y', strtotime($data->created_at));
            })            
            ->rawColumns(['loan_number','settlement_file','created_at'])
            ->make(true);
    }
    




public function settlement_forms(){
  
return view('Settlements.download');
  
  
} 

/**
     * Here follows the actions to be peformed by the Admin
     * Review Loan Applications which have not been approved 
     * 
     * 
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */


public function review(){
    $status = Approvals::where('cfo_decision', "=", 0)->exists();
    if($status){
$loan_status = Approvals::where('cfo_decision', "=", 0)->first();





$loan_applications = web_loan_application::where('loan_number', "=", $loan_status->loan_number)->where('approved',"=",5)->orWhere('approved',"=",6)->first();
if($loan_status && $loan_applications){
return view('LoanApprovals_CFO.index',[
    'loan_applications' => $loan_applications,
    'loan_status' => $loan_status
]);

}
    }
else{
    toast('All Loans have been reviewed!','success');
    return redirect()->route('admindashboard');    

} 
}




/**
     * Here follows the actions to be peformed by the Admin
     * Review Loan Applications Attachments For Each Employee 
     * Retrieving KYC attachments associated with each user 
     * (NRC,Payslip, and Utility)
     * 
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */



public function reviewed_loans(){

    $status = Approvals::where('completed_by_admin', "=", 0)->where('cfo_decision',"!=",0)->exists();
  
    if($status){
    $loan_status = Approvals::where('completed_by_admin', "=", 0)->where('cfo_decision', "=", 1)->orWhere('cfo_decision', "=", 4)->first();
    $loan_applications = web_loan_application::where('loan_number', "=", $loan_status->loan_number)->where('approved',"=",7)->orWhere('approved',"=",8)->first();
   
    
    //($loan_applications);
    
    if($loan_status && $loan_applications){
    return view('LoanApprovals_ADMIN.index',[
        'loan_applications' => $loan_applications,
        'loan_status' => $loan_status
    ]);
    
    }
}
    else{
        toast('You have no Loans to review!','success');
        return redirect()->route('admindashboard');    
    
    } 
    }
  


    public function downloadZip(){
       
        $zip = new ZipArchive();
    
    
        $fileName = "terms_conditions/FORMS/Loan_agreement_forms.zip";
        try{
             if ($zip->open(public_path($fileName), ZipArchive::CREATE) === true) {
                 $files = File::files(public_path("terms_conditions/FORMS"));
     
     
                 foreach ($files as $key => $value) {
                     $relativeNameInZipFile = basename($value);
                     $zip->addFile($value, $relativeNameInZipFile);
                 }
     
                 $zip->close();
             }
     
             return response()->download(public_path($fileName));
         
         }
     
     
         catch(\Throwable $e){
            toast('No Loan Agreement Forms have been found!','error');
            return redirect()->back();      
         }
     
    
    }









/**
     * Here follows the actions to be peformed by the Admin
     * Approve Loan Applications if satsfied 
     * 
     * 
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */


public function approve(Request $request){
    $request->validate([
        'loan_number' => 'required|string',
        'admin_decision' => 'required|string'
    ]);

// 

    $loan_applications = web_loan_application::where('loan_number',"=",$request->loan_number)->where('approved',"=",7)->orWhere('approved',"=",8)->first();
    
    if($request->admin_decision == 'yes'){
    $loan_applications-> approved = 1;
    $loan_applications->save();
    


    ## Mark as Complete in Approvals Table 
       $loan_data = Approvals::where('loan_number',"=",$request->loan_number)->first();
       $loan_data->update([
       'completed_by_admin' => 1
        
      ]); 


    ## Send Email Notification to the user together with the loan number
    ## If Loan Application has been approved successfully

    
// Compile the loan agreement form  
$hold_loan = web_loan_application::where('loan_number',"=",$request->loan_number)->first(); 
$applicant = reg_employee_mst::find($hold_loan->employee_id);
$rep = auth()->user()->firstname. ' '.auth()->user()->lastname;


// Compile Loan Agreement if Signature Exists For Both Client and Company Represantative
if ($applicant->hasBeenSigned() && auth()->user()->hasBeenSigned()) { 


  // Loan Agreement For Private or Civil Servants
 
if($loan_applications->loan_type == 1 || $loan_applications->loan_type == 3){
$pdf = Pdf::loadView('LoanTerms.company_payroll', compact('hold_loan','applicant','rep'))
->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled' => true]);
$attachment = $pdf->output();

$fileName = $request->loan_number.'.pdf';

Storage::disk("loan_agreement_forms")->put('FORMS/'.$fileName, $attachment);
}


// Loan Agreement For Auto Loans
 
if($loan_applications->loan_type == 2){
    $pdf = Pdf::loadView('LoanTerms.company_auto', compact('hold_loan','applicant','rep'))
    ->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled' => true]);
    $attachment = $pdf->output();
    
    $fileName = $request->loan_number.'.pdf';
    
    Storage::disk("loan_agreement_forms")->put('FORMS/'.$fileName, $attachment);
    }
}

## Send Email Notification to the user together with the loan number
    ## If Loan Application has been approved successfully
    $loan_number =  $loan_applications->loan_number;
    $email_notification = reg_employee_mst::find($loan_applications->employee_id);
    $loan_applicant_name = $email_notification->firstname. ' '.$email_notification->lastname;
    $email_notification->notify(new approve($loan_number,$loan_applicant_name));


    if (!$applicant->hasBeenSigned()) { 
     toast('Loan Approved with no Agreement Form. Cause there is no signature for applicant','success');
     return redirect()->route('reviewed_loans');  
    }

    if ($applicant->hasBeenSigned()) { 
        toast('Loan Approved with Agreement Form. Applicant Notified Via Email','success');
        return redirect()->route('reviewed_loans');  
       }
    }

elseif($request->admin_decision == 'no'){

 ## Mark as Complete in Approvals Table 
 $loan_data = Approvals::where('loan_number',"=",$request->loan_number)->first();
 $loan_data->update([
 'completed_by_admin' => 1
  
]); 


    $loan_applications-> approved = 3; //Denied Loan 
    $loan_applications->save();
    
    ## Send Email Notification to the user together with the loan number
    ## If Loan Application has been denied successfully
    $loan_number =  $loan_applications->loan_number;
    $email_notification = reg_employee_mst::find($loan_applications->employee_id);
    $loan_applicant_name = $email_notification->firstname. ' '.$email_notification->lastname;
    $email_notification->notify(new denie($loan_applicant_name));
    toast('Loan Denied Successfully. Client Notified Via Email!','success');
    return redirect()->back();  
     }


     

else{
    toast('Invalid Request','error');
    return redirect()->back();  
}
    }

  

/**
     * Here follows the actions to be peformed by the Admin
     * Denie Loan Applications if not satsfied 
     * 
     * 
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */






/**
     * Here follows the actions to be peformed by the Client
     * Settle payments using airtel Mobile Money (LocalMobileMoney) 
     * Using Airtel MoMo API (https://developers.airtel.africa/documentation)
     * 
     * 
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */


public function collectionsPost(Request $request){

## Bearer Token
## Creating Bearer Token
    $airtelTokenAccessAttempt = Http::withHeaders([
    "Content-Type" => "application/json",
    "Accept" => "*/*",
   
])->post('https://openapiuat.airtel.africa/auth/oauth2/token', [
    "client_id"=> config('airtelMoMoAPI.client_id'),
    "client_secret"=> config('airtelMoMoAPI.client_secret'),
    "grant_type"=> config('airtelMoMoAPI.grant_type'),
]);



## Check if the one testing this system has configured any means of collecting payments
## from customers. If not stop the process

if ($airtelTokenAccessAttempt->status() != 200) {

            toast('Service unavailable! Transaction Failed','error');
            return redirect()->back();  
    

//return "This system uses airtel Mobile Money API (https://developers.airtel.africa/login) for testing transactions
//in collecting funds from customers. Now it seems you have not configured any API's in `config -> airtelMoMoAPI` and in `.env`";
    }
     else {

        $access_token  = $airtelTokenAccessAttempt->object();

        ## Bearer Token Created Successfully
        $token = $access_token->access_token;

        ## Unique Transaction Id sent to airtel from customer
        $id = random_int(1000000000, 9999999999);

        


        /**
         * REQUEST TO PAY
         * The transaction will be executed once the payer has authorized the payment
         *  The request to pay will be in status PENDING until the transaction is authorized
         * or declined by the payer or it is timed out by the system.
         */
       

    $airtelRequestToPayAttempt = Http::withHeaders([
    "Content-Type" => "application/json",
    "Accept" => "*/*",
    "X-Country" => "ZM",
    "X-Currency" => "ZMW",
    "Authorization" => "Bearer ".$token,
   
])->post('https://openapiuat.airtel.africa/merchant/v1/payments/', [

    "reference" => "$request->reference",
      "subscriber" =>[
        "country" => "ZM",
        "currency" => "ZMW",
        "msisdn" => "$request->phone_number"
      ],
      "transaction" => [
        "amount" =>  "$request->amount",
        "country" => "ZM",
        "currency" => "ZMW",
        "id" => "$id"
      ]
]);

    

        ## Payment status requests
        $paymentRequest = $airtelRequestToPayAttempt->object();
        ## Transaction Id
        $paymentRequest->data->transaction->id;
        ## Message
        $paymentRequest->data->transaction->status;

       ## Payment status requests

        return $paymentRequest = $airtelRequestToPayAttempt->object();
    }
}
}

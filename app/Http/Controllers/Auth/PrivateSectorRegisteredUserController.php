<?php

namespace App\Http\Controllers\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\reg_employee_mst;
use App\Models\reg_employee_attachment;
use App\Models\web_loan_application;
use App\Models\website_profile;
use App\Models\api_logins_mst;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection ;
class PrivateSectorRegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.private_sector');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'loan_amt' => ['required', 'numeric','max:1000'],
            'tenure_months' => ['required', 'numeric'],
            'lower_facility_fee' => ['required', 'numeric'],
            'higher_facility_fee' => ['required', 'numeric'],
            'total_repayments_amt' => ['required', 'numeric'],
            'emi' => ['required', 'numeric'],
            'firstname' => ['required', 'string','max:255'],
            'lastname' => ['required', 'string','max:255'],
            'nrc' => ['required', 'string'],
            'email' => ['required', 'email','unique:reg_employee_mst'],
            'dob' => ['required', 'before:2005-01-01'],
            'phone' => ['required', 'string'],
            'province' => ['required', 'string'],
            'town' => ['required', 'string'],
            'address' => ['required', 'string'],
            'employee_number' => ['required', 'string'],
            'job_title' => ['required', 'string','max:255'],
            'ministry' => ['required', 'string'],
            'gross' => ['required','numeric','gte:net,basic'],
            'net' => ['required','numeric','lt:gross'],
            'basic' => ['required','numeric','lte:gross'],
            'bankname' => ['required', 'string','max:255'],
            'bank_branch' => ['required', 'string','max:255'],
            'account_name' => ['required', 'string','max:255'],
            'account_number' => ['required', 'string','max:255'],
            'momo_name' => ['required', 'string','max:255'],
            'mobile_money_number' => ['required', 'string'],
            'base_station' => ['required', 'string'],
            'employer_name' => ['required', 'string','max:255'],
            'employer_number' => ['required', 'string'],
            'nextofkin_firstname' => ['required', 'string','max:255'],
            'nextofkin_lastname' => ['required', 'string','max:255'],
            'nextofkin_number' => ['required', 'string'],
            'nextofkin_address' => ['required', 'string','max:255'],
            'next_of_kin_relationship' => ['required', 'string','max:255'],
            'payslip1' => ['required', 'mimes:pdf','max:10200'],
            'payslip2' => ['required','mimes:pdf','max:10200'],
            'bank_statement' => ['required', 'mimes:pdf','max:10200'],
            'passport_photo' => ['required', 'mimes:png,jpeg','max:10200'],
            'nrc_file' => ['required', 'mimes:pdf','max:10200'],
            'password' => ['required', 'confirmed',Rules\Password::defaults()],
        ],
        [
            'loan_amt.max' => 'Loan amount cannot exceed K1000 for first-time clients.',
            'dob.before' => 'The date of birth must be before 1st January 2005',
			'email.unique' => "You are already a client, just Log In and apply.",
			'phone.unique' => 'Only first time clients needs to do this. Returning clients just needs to log in and apply',
            'gross.gte' => 'The gross amount must be greater than or equal to basic or net',
            'net.lt' => 'The net amount must be less than gross',
            'basic.lte' => 'The basic amount must be less than or equal to gross'
			
		]);


        // Save User
        $user = new reg_employee_mst;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->nrc = str_replace('/','',$request->nrc);
        $user->mannumber = $request->employee_number;
        $user->dob = $request->dob;
        $user->province_id = $request->province;
        $user->town_id = $request->town;
        $user->address = $request->address;
        $user->base_station_town = $request->base_station;
        $user->base_station_address = $request->work_address;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->position = $request->job_title;
        $user->net_salary = $request->net;
        $user->gross = $request->gross;
        $user->basic = $request->basic;
        $user->next_of_kin_fname = $request->nextofkin_firstname;
        $user->next_of_kin_lname = $request->nextofkin_lastname;
        $user->next_of_kin_relationship = $request->next_of_kin_relationship;
        $user->next_of_kin_phone = $request->nextofkin_number;
        $user->next_of_kin_address = $request->nextofkin_address;
        $user->bank_id = $request->bankname;
        $user->bank_branch_id = $request->bank_branch;
        $user->bank_account_no = $request->account_number;
        $user->bank_account_name = $request->account_name;
        $user->mobile_money_no = $request->mobile_money_number;
        $user->mobile_money_name = $request->momo_name;
        $user->company_id = $request->ministry;
        $user->supervisors_name = $request->employer_name;
        $user->supervisors_number = $request->employer_number;
        $user->save();   

         $user_profile = website_profile::create([
            'nrc' => str_replace('/','',$request->nrc),
        ]);

                
         $reg_employee = reg_employee_mst::find($user->employee_id);
         $api_logins = new api_logins_mst;
         $api_logins->nrc = str_replace('/','',$request->nrc);
         $api_logins->password = Hash::make($request->password);
         //$reg = $reg_employee->api_logins_mst(); 
         //return  $reg->get();
        $reg = $reg_employee->api_logins_mst()->save($api_logins);
          

// Add Loan

/**
 * Generate the Loan Number in the system and save it in the database
 * If the Loan Number exists in the database tell the user to resubmit
 * 
**/
$number = random_int(1000000000, 9999999999);
$loannumber = $user->employee_id.$number;

if(web_loan_application::where('loan_number',"=",$loannumber)->exists()){
    toast('Whoops something went wrong, try again!','error');
    return redirect('/');   

} 


/**
* Check if the user has already submitted a loan. 
* If Yes Denie application and redirect back with Msg.
* 
**/

elseif (web_loan_application::where('employee_id',"=",$user->employee_id)->exists()){
    toast('It seems You have a pending Loan. First settle this Loan then you can apply later!','error');
    return redirect('/');     
 } 

 
/**
* Proceed with Loan submission if the above two conditions  
* are not met and everything is ok.
* 
**/



else{

 $loan_application= new web_loan_application;
 $loan_application->loan_type = 3;// $request->loan_type;
 $loan_application->employee_id = $user->employee_id;
 $loan_application->months = $request->tenure_months;
 $loan_application->amount = $request->loan_amt;
 $loan_application->loan_amount = $request->total_repayments_amt;
 $loan_application->emi = $request->emi;
 //$loan_application->payment_mode = $request->payment_mode_id;
 $loan_application->mobile_money_number = $request->mobile_money_number;
 $loan_application->mobile_monney_name = $request->momo_name;
 $loan_application->loan_number = $loannumber;
 $loan_application->payslip1 = $request->payslip1->store('payslips');
 $loan_application->payslip2 = $request->payslip2->store('payslips');
 $loan_application->bank_statement = $request->bank_statement->store('bank_statement');
 $loan_application->approved = 0;
 $loan_application->due_date = Carbon::now()->addMonths($request->tenure_months)->format('d-m-Y');
 $loan_application->save();



 //Submiting NRC File in reg_employee_attachments 
 //$attachments_nrc = reg_employee_mst::find(decrypt($id));
 $nrc = new reg_employee_attachment;
 $nrc->attachment_name=$request->nrc_file->store('nrc');
 $nrc->document_type_id=1;
 $user->reg_employee_attachment()->save($nrc);


 // Submitting Passport Photo 
 $user->profilepic = $request->passport_photo->store('passportphoto');
 $user->save();
 Alert::success('Success Loan Application', 'Your Loan has been submitted successfully. Wait for the email confirmation once approved!');
 return redirect('/');   
 
}






      //Authenticate and Log In the user to the dashboard now


     event(new Registered($user));
        

     Auth::login($user);



   return redirect(RouteServiceProvider::HOME);

    }
}

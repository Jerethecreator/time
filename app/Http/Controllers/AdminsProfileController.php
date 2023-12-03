<?php

namespace App\Http\Controllers;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use App\Models\api_logins_mst;
class AdminsProfileController extends Controller
{
    //
    public function index(){
        return view('AdminsProfile.index');
    }


    public function create(){
        return view('AdminsProfile.change_password');   
    }

    public function store(Request $request){
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed',
           
        ]);


        $user_hashed_password = api_logins_mst::where('employee_id',"=",auth()->user()->employee_id)->firstOrFail();

        if (Hash::check($request->old_password,$user_hashed_password->password)) {
            $user_hashed_password->password = Hash::make($request->password);
            $user_hashed_password->save();
            toast('Your password has been changed successfully!','success');
            return redirect()->route('admindashboard');
        } 
        
        else {
            toast('The old password is incorrect!','error');
            return redirect()->route('admindashboard');
        }

       

         
    }
}

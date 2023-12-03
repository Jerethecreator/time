<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class web_loan_application extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'company_id',
        'tenure_months',
        'loan_amt',
        'payment_mode_id',
        'mobile_money_no',
        'tenure_months',
        'mobile_money_name',
        'approved',
        'loan_number',
    ];
}



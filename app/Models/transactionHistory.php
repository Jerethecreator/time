<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactionHistory extends Model
{
    use HasFactory;

 /**
     * The attributes that are mass assignable.
     *
          */

    protected $fillable = [
        'loan_number',
        'loan_amount',        
        'transaction_id',
        'payment_method',
        'user_id',
        'balance_due',
        'reference_number'
    ];



    
}





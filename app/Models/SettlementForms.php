<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettlementForms extends Model
{
    use HasFactory;

   

    protected $fillable = [
        'loan_number',
        'settlement_file',
        'user_id'
    ];

}

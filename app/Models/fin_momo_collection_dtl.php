<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fin_momo_collection_dtl extends Model
{
    use HasFactory;
    protected $primaryKey = 'request_id';

     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
       'mobile_no',
       'loan_ref_no',
       'amount',
       'currency',
       'external_ref_no',
       'external_system',
       'comments',
       'status',
       

    ];

}

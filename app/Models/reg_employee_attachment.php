<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reg_employee_attachment extends Model
{
    use HasFactory;

    public function reg_employee_mst()
    {
        return $this->belongsTo(reg_employee_mst::class,'employee_id','employee_id');
    }



   

protected $primaryKey = 'employee_attachment_id';
public $timestamps = false;     

}

<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class api_logins_mst extends Authenticatable
{
    use HasFactory, Notifiable;

    public function reg_employee_mst()
    {
         //Foreign key and the primary id
        return $this->belongsTo(reg_employee_mst::class,  'employee_id','employee_id');
    }



    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nrc',
        'password',
        'employee_id'
       ];

protected $table = 'api_logins_mst';
protected $primaryKey = 'no';
public $timestamps = false;   

   
}

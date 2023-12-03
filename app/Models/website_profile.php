<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class website_profile extends Model
{
    use HasFactory;

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nrc',
        'profilepic',
        
       ];


        
    /**
     * The attributes that are hidden.
     *
     * @var array
     */
    protected $hidden = [        
        'token',               
       ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FnfpInfo extends Model
{
    use HasFactory;

    protected $table = 'fnfp_infos'; //  table name

    protected $fillable = [
        'user_id', 
        'name', 
        'account_no', 
        'employee_contribution', 
        'employer_contribution'
    ];
}

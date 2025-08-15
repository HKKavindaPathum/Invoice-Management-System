<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_name',
        'company_name',
        'company_phone',
        'country',
        'state',
        'city',
        'zip_code',
        'fax_number',
        'company_address',
        'app_logo',
    ];
}

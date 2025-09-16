<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'country',
        'passport_no',
        'address',
        'company_name',
        'mobile_no',
        'email',
        'note',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

}

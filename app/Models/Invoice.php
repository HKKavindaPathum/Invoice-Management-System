<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {
    use HasFactory;

    protected $fillable = [
        'client_id', 'invoice_date', 'due_date', 'status', 'total_amount', 'final_amount','discount_type', 'discount', 'note'
    ];

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function productInvoices() {
        return $this->hasMany(ProductInvoice::class);
    }
}

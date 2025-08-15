<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('product_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade'); // Foreign key
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Foreign key
            $table->integer('quantity');
            $table->integer('days');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('product_invoices');
    }
};

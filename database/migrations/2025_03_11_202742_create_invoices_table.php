<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->enum('status', ['unpaid', 'paid', 'partially_paid', 'overdue', 'processing']);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('final_amount', 10, 2);
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount', 10, 2)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('invoices');
    }
};

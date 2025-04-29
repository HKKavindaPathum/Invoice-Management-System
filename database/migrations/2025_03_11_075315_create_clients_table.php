<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('clients', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('first_name');
        $table->string('last_name');
        $table->string('country')->nullable();
        $table->string('passport_no')->nullable();
        $table->text('address')->nullable();
        $table->string('company_name')->nullable();
        $table->string('mobile_no')->nullable();
        $table->string('email')->nullable();
        $table->text('note')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};

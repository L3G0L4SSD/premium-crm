<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('email')->unique();
        $table->string('password');
        $table->string('full_name')->nullable();
        $table->string('company_name')->nullable();
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->string('status')->default('pending_profile');
        $table->unsignedBigInteger('department_id')->nullable();
        $table->unsignedBigInteger('assigned_to')->nullable();
        $table->unsignedBigInteger('created_by')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

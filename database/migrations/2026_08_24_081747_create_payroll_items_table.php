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
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('officer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('salary_profile_id')->nullable()->constrained()->nullOnDelete();
            // Snapshot of the salary profile at the moment this payroll was
            // generated — a later salary change must never rewrite a past
            // month's payroll, same principle as Application.package_price.
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('total_allowance', 15, 2);
            $table->decimal('total_deduction', 15, 2);
            $table->decimal('net_salary', 15, 2);
            $table->timestamps();

            $table->unique(['payroll_id', 'officer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
    }
};

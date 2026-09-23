<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Accounting record only — there is no payment gateway. This table
     * exists so a Cash/Bank/Mobile Banking disbursement that already
     * happened outside the system has somewhere to be recorded.
     */
    public function up(): void
    {
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('payroll_id')->nullable()->constrained()->nullOnDelete();
            $table->date('month');
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->string('payment_method', 20);
            $table->string('transaction_reference')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('paid_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Allowances/deductions are flat columns here rather than separate
     * normalized tables — the spec's own field list for a salary profile
     * (section 18) names four fixed allowance categories and one deduction
     * total, so a join table would model line items the business logic
     * never actually needs.
     */
    public function up(): void
    {
        Schema::create('salary_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('house_allowance', 15, 2)->default(0);
            $table->decimal('transport_allowance', 15, 2)->default(0);
            $table->decimal('mobile_allowance', 15, 2)->default(0);
            $table->decimal('other_allowance', 15, 2)->default(0);
            $table->decimal('deduction', 15, 2)->default(0);
            $table->date('effective_from');
            $table->string('status', 20)->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_profiles');
    }
};

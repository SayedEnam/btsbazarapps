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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number', 20)->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            // The officer currently handling this application — independent of
            // (and reassignable separately from) the customer's permanent
            // referral relationship in the `referrals` table.
            $table->foreignId('officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('package_id')->constrained()->restrictOnDelete();
            // Snapshot of the package price at the moment of application —
            // must never change even if the package's price changes later.
            $table->decimal('package_price', 15, 2);
            $table->date('application_date');
            $table->string('status', 20)->default('pending')->index();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->string('rejection_reason', 500)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};

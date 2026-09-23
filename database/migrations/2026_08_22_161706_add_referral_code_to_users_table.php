<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * A Marketing Officer's referral code lives on `users` for now — a full
     * Officer profile (employee id, department, designation, salary) is
     * Phase 4's job, but the referral link/code needs to exist today so
     * customer registration can capture a real, permanent referral
     * relationship instead of an unresolved raw string.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 20)->nullable()->unique()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('referral_code');
        });
    }
};

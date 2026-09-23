<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Referral codes used to be generated as "REF-0001"; the client asked
     * for the prefix to be dropped everywhere, including codes already
     * issued before this change shipped — otherwise older officers would
     * be stuck with a "REF-" code forever while new ones get a clean
     * number, which is exactly the inconsistency being fixed.
     */
    public function up(): void
    {
        DB::table('users')
            ->where('referral_code', 'like', 'REF-%')
            ->update(['referral_code' => DB::raw("SUBSTRING(referral_code, 5)")]);

        DB::table('referrals')
            ->where('referral_code', 'like', 'REF-%')
            ->update(['referral_code' => DB::raw("SUBSTRING(referral_code, 5)")]);
    }

    /**
     * Restores the "REF-" prefix on any code that is purely numeric — the
     * only shape this migration's forward pass could have produced.
     */
    public function down(): void
    {
        DB::table('users')
            ->where('referral_code', 'regexp', '^[0-9]+$')
            ->update(['referral_code' => DB::raw("CONCAT('REF-', referral_code)")]);

        DB::table('referrals')
            ->where('referral_code', 'regexp', '^[0-9]+$')
            ->update(['referral_code' => DB::raw("CONCAT('REF-', referral_code)")]);
    }
};

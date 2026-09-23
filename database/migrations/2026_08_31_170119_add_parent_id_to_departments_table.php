<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A single level of nesting only (a department's parent must itself be
     * top-level) — enforced in DepartmentForm, not the schema. That keeps
     * the department picker on the Officer form a simple two-tier list
     * instead of an arbitrarily deep tree.
     */
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('departments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
        });
    }
};

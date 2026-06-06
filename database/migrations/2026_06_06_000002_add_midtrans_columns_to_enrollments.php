<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('payment_id');
            $table->string('payment_type')->nullable()->after('snap_token');
            $table->datetime('transaction_time')->nullable()->after('payment_type');
            $table->datetime('paid_at')->nullable()->after('transaction_time');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'payment_type', 'transaction_time', 'paid_at']);
        });
    }
};

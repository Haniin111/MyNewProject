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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('paid_at')->nullable()->after('payment_status');
            $table->text('payment_notes')->nullable()->after('paid_at');
            $table->timestamp('delivered_at')->nullable()->after('payment_notes');
            $table->unsignedBigInteger('delivered_by')->nullable()->after('delivered_at');
            
            // Add foreign key for delivered_by
            $table->foreign('delivered_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivered_by']);
            $table->dropColumn(['paid_at', 'payment_notes', 'delivered_at', 'delivered_by']);
        });
    }
};

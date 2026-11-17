<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('public_id')->nullable()->unique()->after('id');
            $table->string('customer_email')->nullable();
            $table->unsignedTinyInteger('table_number')->nullable();
            $table->string('payment_status')->default('pending');
            $table->timestamp('payment_expires_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['public_id']);
            $table->dropColumn([
                'public_id',
                'customer_email',
                'table_number',
                'payment_status',
                'payment_expires_at',
            ]);
        });
    }
};

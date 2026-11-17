<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_id')->nullable();
            $table->string('name_en')->nullable();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('total_price')->nullable();
        });

        DB::table('categories')->whereNull('name_id')->update([
            'name_id' => DB::raw('name'),
            'name_en' => DB::raw('name'),
        ]);

        DB::table('menus')->whereNull('description_id')->update([
            'description_id' => DB::raw('description'),
            'description_en' => DB::raw('description'),
        ]);

        DB::table('orders')->whereNull('total_price')->update([
            'total_price' => DB::raw('total_amount'),
        ]);
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_id', 'name_en']);
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['description_id', 'description_en']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('total_price');
        });
    }
};

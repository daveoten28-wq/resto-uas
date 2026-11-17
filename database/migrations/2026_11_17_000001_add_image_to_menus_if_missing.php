<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('menus', 'image')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->string('image')->nullable();
            });
        }
    }

    public function down(): void
    {
        // The column may have existed before this migration, so do not drop it on rollback.
    }
};

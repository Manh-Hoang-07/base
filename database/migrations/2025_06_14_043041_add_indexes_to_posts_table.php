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
        Schema::table('posts', function (Blueprint $table) {
            // Index cho status (thường xuyên filter)
            $table->index('status');

            // Index cho created_at (sắp xếp theo thời gian)
            $table->index('created_at');

            // Index cho user_id (filter theo user)
            $table->index('user_id');

            // Composite index cho status + created_at (filter + sort)
            $table->index(['status', 'created_at']);

            // Index cho name (search)
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Xóa các indexes
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['name']);
        });
    }
};

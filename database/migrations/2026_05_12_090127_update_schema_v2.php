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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable();
            }
            if (!Schema::hasColumn('users', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable();
            }
            if (!Schema::hasColumn('users', 'fcm_token')) {
                $table->string('fcm_token')->nullable();
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'note')) {
                $table->text('note')->nullable();
            }
        });

        Schema::table('portfolios', function (Blueprint $table) {
            if (Schema::hasColumn('portfolios', 'user_id')) {
                $table->renameColumn('user_id', 'freelancer_id');
            }
        });

        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'freelancer_id')) {
                $table->foreignId('freelancer_id')->after('client_id')->constrained('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'latitude', 'longitude', 'fcm_token']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('note');
        });

        Schema::table('portfolios', function (Blueprint $table) {
            $table->renameColumn('freelancer_id', 'user_id');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['freelancer_id']);
            $table->dropColumn('freelancer_id');
        });
    }
};

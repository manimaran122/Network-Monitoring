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
        Schema::table('monitors', function (Blueprint $table) {
            $table->integer('check_interval')->default(300); // Seconds
            $table->integer('request_timeout')->default(30); // Seconds
            $table->string('method')->default('GET');
            $table->string('accepted_status_codes')->default('200-299');
            $table->string('authentication_method')->nullable();
            $table->string('authentication_user')->nullable();
            $table->string('authentication_password')->nullable();
            $table->boolean('verify_ssl')->default(true);
            $table->boolean('follow_redirects')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitors', function (Blueprint $table) {
            $table->dropColumn([
                'check_interval', 'request_timeout', 'method', 
                'accepted_status_codes', 'authentication_method', 
                'authentication_user', 'authentication_password', 
                'verify_ssl', 'follow_redirects'
            ]);
        });
    }
};

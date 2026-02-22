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
            $table->string('type')->default('ping');
            $table->string('group')->nullable()->default('default');
            $table->json('tags')->nullable();
            $table->boolean('email_notification')->default(false);
            $table->boolean('sms_notification')->default(false);
            $table->boolean('voice_notification')->default(false);
            $table->boolean('push_notification')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitors', function (Blueprint $table) {
            $table->dropColumn(['type', 'group', 'tags', 'email_notification', 'sms_notification', 'voice_notification', 'push_notification']);
        });
    }
};

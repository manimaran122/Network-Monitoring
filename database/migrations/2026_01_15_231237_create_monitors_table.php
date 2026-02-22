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
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip_address');
            $table->integer('port')->nullable()->default(80);
            $table->enum('status', ['online', 'offline', 'warning', 'maintenance'])->default('online');
            $table->float('uptime')->default(100.0);
            $table->float('latency')->default(0);
            $table->float('jitter')->default(0);
            $table->float('packet_loss')->default(0);
            $table->date('ssl_expiry')->nullable();
            $table->timestamp('last_check')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitors');
    }
};

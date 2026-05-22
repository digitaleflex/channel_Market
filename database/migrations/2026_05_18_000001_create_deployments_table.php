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
        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->string('commit_sha');
            $table->string('commit_message')->nullable();
            $table->string('status'); // e.g. pending, deploying, success, failed
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->integer('duration')->nullable(); // in seconds
            $table->text('log_output')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('account_id')->unique();
            $table->string('access_type')->default('user');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->json('media_advertisement')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();
        });
        
        Schema::create('windows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->integer('limit')->default(100);
            $table->string('status')->default('open');
            $table->foreignId('queue_id')->constrained('queues')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('window_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_id')->constrained('queues')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('window_id')->constrained('windows')->onDelete('cascade');            
            $table->string('window_name')->nullable(); 
            $table->boolean('can_close_own_window')->default(false);
            $table->boolean('can_close_any_window')->default(false);
            $table->boolean('can_close_queue')->default(false);
            $table->boolean('can_clear_queue')->default(false);
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('code');
            $table->string('name')->nullable();
            $table->string('status')->default('Waiting');
            $table->integer('ticket_number');
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('window_id')->constrained('windows')->onDelete('cascade');
            $table->foreignId('queue_id')->constrained('queues')->onDelete('cascade');
            $table->timestamp('called_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('window_access');
        Schema::dropIfExists('windows');
        Schema::dropIfExists('queues');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('password_reset_tokens');
    }
};

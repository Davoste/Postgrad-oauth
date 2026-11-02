<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('client_id', 100)->unique();
            $table->string('client_secret'); // hashed
            $table->text('redirect_uri'); // one URI, or several newline-separated
            // First-party clients (your own Moodle) can skip the consent
            // screen — the portal already IS their identity, so asking
            // "allow Moodle to access your account?" is just friction.
            $table->boolean('trusted')->default(false);
            $table->boolean('revoked')->default(false);
            $table->timestamps();
        });

        Schema::create('oauth_auth_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('oauth_client_id')->constrained('oauth_clients')->cascadeOnDelete();
            $table->text('redirect_uri');
            $table->string('scopes')->nullable();
            $table->timestamp('expires_at');
            $table->boolean('revoked')->default(false);
            $table->timestamps();
        });

        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token_hash', 64)->unique(); // sha256 of the bearer token
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('oauth_client_id')->constrained('oauth_clients')->cascadeOnDelete();
            $table->string('scopes')->nullable();
            $table->timestamp('expires_at');
            $table->boolean('revoked')->default(false);
            $table->timestamps();
        });

        Schema::create('oauth_refresh_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token_hash', 64)->unique();
            $table->foreignId('oauth_access_token_id')->constrained('oauth_access_tokens')->cascadeOnDelete();
            $table->timestamp('expires_at');
            $table->boolean('revoked')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oauth_refresh_tokens');
        Schema::dropIfExists('oauth_access_tokens');
        Schema::dropIfExists('oauth_auth_codes');
        Schema::dropIfExists('oauth_clients');
    }
};

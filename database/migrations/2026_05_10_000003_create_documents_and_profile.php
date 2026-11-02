<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->morphs('documentable');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('category')->default('general');
            $table->string('title');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('staff_no')->nullable()->after('phone');
            $table->string('id_no')->nullable()->after('staff_no');
            $table->boolean('is_active')->default(true)->after('id_no');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('avatar_path')->nullable()->after('last_login_at');
            $table->string('title')->nullable()->after('name');
            $table->text('bio')->nullable()->after('avatar_path');
            $table->string('office')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'staff_no', 'id_no', 'is_active', 'last_login_at', 'avatar_path', 'title', 'bio', 'office']);
        });
        Schema::dropIfExists('documents');
    }
};

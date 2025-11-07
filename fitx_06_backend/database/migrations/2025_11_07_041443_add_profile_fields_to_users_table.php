<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('password');
            $table->float('weight')->nullable()->after('avatar');
            $table->string('goal')->nullable()->after('weight');
            $table->foreignId('package_id')->nullable()->after('goal')
                  ->constrained('packages')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn(['avatar', 'weight', 'goal', 'package_id']);
        });
    }
};

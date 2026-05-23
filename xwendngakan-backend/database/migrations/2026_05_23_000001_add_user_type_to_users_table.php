<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['mobile', 'portal'])->default('mobile')->after('is_approved');
        });

        // Set existing portal users (those with is_approved=false and no institution-linked creation)
        // We default all existing to mobile; admin can change portal ones manually if needed.
        // New registrations will correctly tag themselves.
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $columns = DB::select('SHOW COLUMNS FROM institutions');
            $existing = collect($columns)->pluck('Field')->toArray();

            // user_id
            if (!in_array('user_id', $existing)) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            // lat / lng
            if (!in_array('lat', $existing)) {
                $table->decimal('lat', 10, 7)->nullable()->after('addr');
            }
            if (!in_array('lng', $existing)) {
                $table->decimal('lng', 10, 7)->nullable()->after('lat');
            }

            // video
            if (!in_array('video', $existing)) {
                $table->text('video')->nullable()->after('img');
            }

            // tuition_plans
            if (!in_array('tuition_plans', $existing)) {
                $table->json('tuition_plans')->nullable()->after('depts');
            }

            // multilingual descriptions
            if (!in_array('desc_en', $existing)) {
                $table->text('desc_en')->nullable()->after('desc');
            }
            if (!in_array('desc_ar', $existing)) {
                $table->text('desc_ar')->nullable()->after('desc_en');
            }

            // stats
            if (!in_array('founded_year', $existing)) {
                $table->unsignedSmallInteger('founded_year')->nullable()->after('video');
            }
            if (!in_array('students_count', $existing)) {
                $table->unsignedInteger('students_count')->nullable()->after('founded_year');
            }
        });
    }

    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn([
                'user_id', 'lat', 'lng', 'video',
                'tuition_plans', 'desc_en', 'desc_ar',
                'founded_year', 'students_count',
            ]);
        });
    }
};

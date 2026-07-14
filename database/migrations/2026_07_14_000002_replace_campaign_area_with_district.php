<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('districts')) {
            Schema::create('districts', function (Blueprint $table): void {
                $table->id();
                $table->string('title');
                $table->timestamps();
            });
        }

        if (Schema::hasColumn('campaigns', 'area') && ! Schema::hasColumn('campaigns', 'district_id')) {
            Schema::table('campaigns', function (Blueprint $table): void {
                $table->unsignedBigInteger('district_id')->nullable()->after('id');
            });

            $districtId = DB::table('districts')->insertGetId([
                'title' => 'غير محدد',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('campaigns')->whereNull('district_id')->update(['district_id' => $districtId]);

            Schema::table('campaigns', function (Blueprint $table): void {
                $table->foreign('district_id')->references('id')->on('districts')->restrictOnDelete();
                $table->dropColumn('area');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('campaigns', 'district_id') && ! Schema::hasColumn('campaigns', 'area')) {
            Schema::table('campaigns', function (Blueprint $table): void {
                $table->dropForeign(['district_id']);
                $table->dropColumn('district_id');
                $table->string('area')->nullable()->after('id');
            });
        }
    }
};

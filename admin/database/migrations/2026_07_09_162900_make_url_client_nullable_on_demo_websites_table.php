<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demo_websites', function (Blueprint $table): void {
            $table->string('url_client', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('demo_websites')
            ->whereNull('url_client')
            ->update(['url_client' => '']);

        Schema::table('demo_websites', function (Blueprint $table): void {
            $table->string('url_client', 255)->nullable(false)->change();
        });
    }
};

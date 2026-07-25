<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->jsonb('output')->nullable()->after('arch');

            $table->dropColumn([
                'cpu_usage',
                'memory_percent',
                'disk_percent',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->float('cpu_usage')->nullable();
            $table->float('memory_percent')->nullable();
            $table->float('disk_percent')->nullable();
            $table->string('status')->nullable();

            $table->dropColumn('output');
        });
    }
};
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
        Schema::table('users', function (Blueprint $table) {
					
					$table->after('email', function ($table) {
            $table->enum('role', ['SEO', 'DEVELOPER', 'MANAGER'])->default('SEO');
						$table->boolean('active')->default(0);
					});	
					
					$table->timestamp('email_verified_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
          $table->dropColumn('role');
					$table->dropColumn('active');
					$table->timestamp('email_verified_at')->nullable()->change();
        });
    }
};

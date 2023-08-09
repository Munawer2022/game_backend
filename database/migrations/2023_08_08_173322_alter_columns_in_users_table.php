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
            $table->integer('type')->after('id')->comment('0->admin,1->user')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};

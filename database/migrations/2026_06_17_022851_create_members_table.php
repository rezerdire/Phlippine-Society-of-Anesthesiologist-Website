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
        Schema::create('members', function (Blueprint $table) {
            $table->string('member_id_no', 4)->primary();
            $table->string('psa_chapter_code', 3)->nullable();
            $table->string('psa_mem_type', 2)->nullable();
            $table->string('mem_last_name', 50)->nullable();
            $table->string('mem_first_name', 50)->nullable();
            $table->string('mem_middle_name', 11)->nullable();
            $table->string('mem_email_address', 56)->nullable();
            $table->string('password', 54)->nullable();
            $table->integer('bal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('community_challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->integer('goal')->default(0);
            $table->integer('reward_points')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('community_challenges');
    }
};

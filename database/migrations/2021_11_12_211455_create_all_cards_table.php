<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_cards', function (Blueprint $table) {
            $table->id();
            $table->uuid('case_id');
            $table->string('company_id');
            $table->string('organization');
            $table->string('employee');
            $table->longText('location')->nullable();
            $table->longText('hazard_description')->nullable();
            $table->string('risked_resource')->nullable();
            $table->string('probability')->nullable();
            $table->longText('impact')->nullable();
            $table->longText('existing_control')->nullable();
            $table->longText('existing_prevention')->nullable();
            $table->string('rating')->nullable();
            $table->longText('other_info');
            $table->string('media')->nullable();
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
        Schema::dropIfExists('all_cards');
    }
}

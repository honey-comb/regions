<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHcRegionContinentTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hc_region_continent_translation', function (Blueprint $table) {
            $table->increments('count');
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->uuid('record_id');
            $table->string('language_code', 2);

            $table->unique(['record_id', 'language_code']);

            $table->string('label');

            $table->foreign('record_id')->references('id')->on('hc_region_continent')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION');

            $table->foreign('language_code')->references('iso_639_1')->on('hc_language')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hc_region_continent_translation');
    }
}

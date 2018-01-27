<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

 use Illuminate\Support\Facades\Schema;
 use Illuminate\Database\Schema\Blueprint;
 use Illuminate\Database\Migrations\Migration;

 class CreatePollsTable extends Migration
 {
     /**
      * Run the migrations.
      *
      * @return void
      */
     public function up()
     {
         Schema::create('polls', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('user_id')->unsigned()->default(0);
             $table->string('title');
             $table->text('description');
             $table->boolean('multichoice')->default(false);
             $table->boolean('closed')->default(false);
             $table->dateTime('ends_at')->nullable()->default(null);
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
         Schema::dropIfExists('polls');
     }
 }

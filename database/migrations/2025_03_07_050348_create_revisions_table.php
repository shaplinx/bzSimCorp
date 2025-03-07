<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRevisionsTable extends Migration {

	public function up()
	{
		Schema::create('revisions', function(Blueprint $table) {
			$table->uuid('id')->primary();
			$table->timestamps();
			$table->bigInteger('revising_id')->unsigned();
			$table->bigInteger('revised_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('revisions');
	}
}
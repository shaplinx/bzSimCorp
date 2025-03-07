<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentsTable extends Migration {

	public function up()
	{
		Schema::create('documents', function(Blueprint $table) {
			$table->id('id');
			$table->string('title', 255);
			$table->string('number', 255)->unique();
			$table->string('author', 255);
			$table->text('about');
			$table->boolean('status');
			$table->string('signed_by', 255);
			$table->string('signed_place', 255);
			$table->timestamp('signed_at');
			$table->timestamps();
			$table->bigInteger('category_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('documents');
	}
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentCategoriesTable extends Migration {

	public function up()
	{
		Schema::create('document_categories', function(Blueprint $table) {
			$table->id('id');
			$table->string('name', 255);
			$table->string('slug', 255)->unique();
			$table->text('description');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('document_categories');
	}
}
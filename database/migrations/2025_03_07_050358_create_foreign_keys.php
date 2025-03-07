<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('documents', function(Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('document_categories')
						->onDelete('cascade')
						->onUpdate('restrict');
		});
		Schema::table('revisions', function(Blueprint $table) {
			$table->foreign('revising_id')->references('id')->on('documents')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('revisions', function(Blueprint $table) {
			$table->foreign('revised_id')->references('id')->on('documents')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('documents', function(Blueprint $table) {
			$table->dropForeign('documents_category_id_foreign');
		});
		Schema::table('revisions', function(Blueprint $table) {
			$table->dropForeign('revisions_revising_id_foreign');
		});
		Schema::table('revisions', function(Blueprint $table) {
			$table->dropForeign('revisions_revised_id_foreign');
		});
	}
}
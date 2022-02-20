<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('loans', function (Blueprint $table) {
			$table->id();
			$table->integer('user_id')->index();
			$table->integer('duration')->nullable();
			$table->double('amount')->default(10, 2);
			$table->enum('is_approved', ['yes', 'no', 'pending'])->default('no');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('loans');
	}
}

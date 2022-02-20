<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPaymentsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('loan_payments', function (Blueprint $table) {
			$table->id();
			$table->string('loan_id')->index();
			$table->integer('duration')->nullable();
			$table->double('amount_paid')->default(10, 2)->nullable();
			$table->datetime('paid_date')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('loan_payments');
	}
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model {
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	public $table = 'loan_payments';
	protected $fillable = [
		'loan_id', 'duration', 'amount_paid', 'paid_date',
	];

}

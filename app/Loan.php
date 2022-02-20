<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model {
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	public $table = 'loans';
	protected $fillable = [
		'user_id', 'duration', 'amount', 'is_approved',
	];

}

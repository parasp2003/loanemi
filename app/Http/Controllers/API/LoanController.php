<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Loan;
use App\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoanController extends BaseController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//
	}

	/**
	 * applyloan with duration and amount.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function applyLoan(Request $request) {

		$validator = Validator::make($request->all(), [
			'duration' => 'required|min:1|max:5200',
			'amount' => 'required|numeric|max:5000000',
		]);

		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}

		try
		{
			$input = $request->all();
			$input['user_id'] = Auth::user()->id;
			$result = Loan::create($input);

			if ($result) {
				$success['loan_id'] = $result->id;
				return $this->sendResponse($success, 'Loan apply successful! Please Approve loan using loan id');
			} else {
				return $this->sendError('error.', ['error' => 'Unable to apply for loan!']);
			}
		} catch (Exception $e) {
			return $this->sendError('error.', ['error' => 'Unable to apply for loan! Please check your request']);
		}

	}

	/**
	 * approveLoan loan approve proccess.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function approveLoan(Request $request) {

		$validator = Validator::make($request->all(), [
			'loan_id' => 'required|numeric|exists:loans,id,user_id,' . Auth::user()->id,
		]);

		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}

		try
		{
			$result = Loan::where(['id' => $request->loan_id])->update(['is_approved' => 'yes']);

			$loan_info = Loan::find($request->loan_id);
			$loan_emi = $this->calculateEmiamount($loan_info->amount, $loan_info->duration);

			$success['LoanAmount'] = '$' . $loan_info->amount;
			$success['Duration'] = $loan_info->duration . ' Weeks';
			$success['EMIAmount'] = '$' . $loan_emi . ' Per Week';

			if ($result) {
				return $this->sendResponse($success, 'Loan Approve successful!');
			} else {
				return $this->sendError('error.', ['error' => 'Unable to Approve loan!']);
			}
		} catch (Exception $e) {
			return $this->sendError('error.', ['error' => 'Unable to Approve loan!']);
		}
	}

	/**
	 * Pay EMI
	 *
	 * @param  Request $request loan_id,emi_amount
	 * @return \Illuminate\Http\Response
	 */
	public function payEmi(Request $request) {

		$loan_info = Loan::find($request->loan_id);
		$loan_emi = $this->calculateEmiamount($loan_info->amount, $loan_info->duration);
		$validator = Validator::make($request->all(), [
			'loan_id' => 'required|numeric|exists:loans,id,user_id,' . Auth::user()->id,
			'emi_amount' => 'required|numeric|max:' . $loan_emi . '|min:' . $loan_emi,
		]);

		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}

		try
		{

			$total_paid = LoanPayment::where('loan_id', $request->loan_id)->sum('amount_paid');
			if (isset($total_paid) && $loan_info->amount != $total_paid) {
				$input = $request->all();
				$input['amount_paid'] = $request->emi_amount;
				$input['loan_id'] = $request->loan_id;
				$input['paid_date'] = date('Y-m-d H:i:s');
				$result = LoanPayment::create($input);

				$success['LoanAmount'] = '$' . $loan_info->amount;
				$success['Duration'] = $loan_info->duration . ' Weeks';
				$success['EMIAmount'] = '$' . $loan_emi . ' Per Week';
				$success['PaidAmount'] = '$' . $total_paid . ' Paid';
				$success['RemainingAmount'] = '$' . ($loan_info->amount - $total_paid) . ' Pending Amount';

				if ($result) {

					return $this->sendResponse($success, 'Loan EMI Successfully Paid!');
				} else {
					return $this->sendError('error.', ['error' => 'Loan EMI Not Paid!']);
				}

			} else {
				$success['LoanAmount'] = '$' . $loan_info->amount;
				$success['PaidAmount'] = '$' . $total_paid . ' Paid';
				return $this->sendResponse($success, 'Your Loan Complited Paid!');
			}

			if ($result) {
				return $this->sendResponse($success, 'Loan EMI Successfully Paid!');
			} else {
				return $this->sendError('error.', ['error' => 'Loan EMI Not Paid!']);
			}
		} catch (Exception $e) {
			return $this->sendError('error.', ['error' => 'Loan EMI Not Paid!']);
		}

	}

	/**
	 * get weekly loan amount.
	 *
	 * @param  \App\Loan  $loan_amount,$paybal
	 * @return \Illuminate\Http\Response
	 */

	private function calculateEmiamount($loan_amount, $duration) {
		return ceil($loan_amount / $duration);
	}

	/**
	 * get weekly loan amount.
	 *
	 * @param  \App\Loan  $loan_amount,$paybal
	 * @return \Illuminate\Http\Response
	 */

	private function checkLoanexist($id) {
		$exist_result = Loan::find($id);
		return $exist_result;
	}

}

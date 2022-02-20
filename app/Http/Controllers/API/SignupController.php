<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class SignupController extends BaseController {
	/**
	 * Register api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function signup(Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => 'required|min:3|max:80',
			'email' => 'required|email|unique:users',
			'password' => 'required|min:6|max:128',
			'confirm_password' => 'required|same:password|min:6|max:128',
		]);

		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}

		$input = $request->all();
		$input['password'] = bcrypt($input['password']);
		$user = User::create($input);
		$success['name'] = $user->name;

		return $this->sendResponse($success, $user->name . ' Signup successfully.');
	}

	/**
	 * Login api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function login(Request $request) {

		$request->validate([
			'email' => 'required|string|email',
			'password' => 'required|string',
		]);

		$credentials = request(['email', 'password']);
		if (!Auth::attempt($credentials)) {
			return response()->json([
				'message' => 'Please Enter Valid Email or password',
			], 401);
		}

		if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
			$user = Auth::user();
			$success['token'] = $user->createToken('LoanApp')->accessToken;
			$success['name'] = $user->name;

			return $this->sendResponse($success, 'login successfully.');
		} else {
			return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
		}
	}

	/**
	 * logout api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function logout(Request $request) {

		if ($request->user()->token()) {
			$request->user()->token()->revoke();
			return response()->json([
				'message' => 'You have been successfully logged out!',
			]);

		} else {
			return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
		}
	}
}
<?php
	
	namespace App\Http\Controllers;
	
	use App\Http\Requests\LoginRequest;
	use App\Models\User;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Hash;
	
	class AuthenticationController extends Controller {
		public function loginView() {
			return view('authentications.login');
		}
		
		public function lockView() {
			return 'locked';
		}
		
		public function loginRequest(LoginRequest $request) {
			$credentials = $request->validated();
			$dbUser = new User();
			$attempts = $dbUser->findForPassport($credentials['usernameOrEmail']);
			$attempts = !empty($attempts) ? $attempts->toArray() : [];
			$attempts['password'] = $credentials['password'];
			if(Auth::attempt($attempts)) {
				$request->session()->regenerate();
				return redirect()->intended(route('app.dashboard'));
			}
			return redirect()->route('auth.login');
		}
		
		public function logoutRequest() {
			return null;
		}
	}

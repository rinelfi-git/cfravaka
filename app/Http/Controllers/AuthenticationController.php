<?php
	
	namespace App\Http\Controllers;
	
	use App\Http\Requests\LoginRequest;
	use App\Models\User;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Hash;
	
	class AuthenticationController extends Controller {
		public function loginView() {
//			$user = new User([
//				'username' => 'rinefli',
//				'name'     => 'Rijaniaina Elie Fidèle',
//				'email'    => 'elierijaniaina@gmail.com',
//				'password' => Hash::make("c'est facile")
//			]);
//			$user->save();
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
			if (Auth::attempt($attempts)) {
				$request->session()->regenerate();
				unset($attempts['password']);
				session($attempts);
				return redirect()->intended(route('app.dashboard'));
			}
			$errorMessage = [];
			if (empty($attempts['email'])) {
				$errorMessage['usernameOrEmail'] = "Le nom d'utilisateur ou le mail n'existe pas";
			} else {
				$errorMessage['password'] = "Le mot de passe est incorrect";
			}
			return $errorMessage;
		}
		
		public function logoutRequest() {
			Auth::logout();
			return redirect()->route('login');
		}
	}

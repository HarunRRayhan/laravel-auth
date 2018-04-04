<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\Http\Controllers\Controller;
use App\User;

class LoginController extends Controller
{
	public function redirectToProvider(){
		return Socialite::driver('github')->redirect();
	}

	public function handleProviderCallback() {
		$user = $this->findOrCreateGitHubUser(
			Socialite::driver('github')->user()
		);

		auth()->login($user);
		return redirect('/');
	}

	protected function findOrCreateGitHubUser($githubUser) {
		$user = User::firstOrNew(['github_id' => $githubUser->id]);

		if($user->exists) return $user;

		$user->fill([
			'username'  => $githubUser->nickname,
			'email'     => $githubUser->email,
			'avatar'    => $githubUser->avatar
		])->save();

		return $user;
	}
}

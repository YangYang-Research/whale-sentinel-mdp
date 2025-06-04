<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Http\Requests\Login;
use App\Models\User;

class LoginController extends Controller
{
    public function getLogin()
    {
        // dd($user = Sentinel::check());
        // if ($user = Sentinel::check()){
        //     $user = Sentinel::getUser()->id;
        //     $username = \DB::table('users')
        //         ->where('id', '=', $user)
        //         ->selectRaw("CONCAT(first_name, ' ', last_name) as full_name")
        //         ->value('full_name');
        //     return redirect()->route('dashboard.index')->with(['status' => 'Welcome '.$username.'!']);;
        // }
        return view("authentications.login");
        
    }

    public function postLogin(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ]);

        $credentials = [
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ];

        try {
            $auth = Sentinel::authenticate($credentials);

            if ($auth) {
                $get_user_email = Sentinel::getUser()->email;
                $user = User::where('email', $get_user_email)->first();

                return redirect()->route('dashboard.index')->with([
                    'status'    => 'Signed in successfully!',
                    'email'     => $user->email,
                    'prev_url'  => $request->redirect_to,
                ]);
            } else {
                return redirect()->route('login')
                    ->withErrors(['message' => 'Incorrect email or password!'])
                    ->withInput();
            }
        } catch (NotActivatedException $e) {
            return redirect()->route('login')
                ->withErrors(['message' => 'Your Account is Inactive!'])
                ->withInput();
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            return redirect()->route('login')
                ->withErrors(['message' => "Too many attempts. Please wait $delay seconds."])
                ->withInput();
        }
    }

    public function postLogout(Request $request)
    {
        Sentinel::logout();
        $request->session()->flush();
        return redirect()->route('login')->with(['status' => 'Logout successfully.']);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Socialite;
use Validator;
use App\Mailers\AppMailer;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectPath = '/dashboard';
    

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }


    public function getRegister()
    {
        $countries = \Countries::getList('en', 'php', 'cldr');
        return view('auth.register', compact('countries'));
    }

    public function getLogout()
    {
        \Session::forget('hasRole');
        Auth::logout();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    

    public function postRegister(Request $request, AppMailer $mailer) // , 
    {
        
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $user = $this->create($request->all());

        // Auth::login($user);


        $mailer->sendEmailConfirmationTo($user);

        flash("Please check your inbox for the confirmation email.");
        
        return redirect('auth/login');
        
    }



    public function confrimEmail($token){
        $user = User::where('confirmation_code', $token)->firstOrFail();
        $user->verified = 1;
        $user->confirmation_code = null;
        $user->save();

        flash("Your account is now confirmed. Please login now.");

        return redirect('auth/login');
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'mobile' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'gender' => 'size:1',
            'dob' => 'required|date',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        if(!isset($data['gender'])) $data['gender'] = 'N';

        $user = new User;

        $user->name = $data['name'];
        $user->gender = $data['gender'];
        $user->dob = $data['dob'];
        $user->mobile = $data['mobile'];

        $user->email = $data['email'];
        $user->password = $data['password'];
 


        $user->save();

        return $user;
    }




   protected function handleUserWasAuthenticated(Request $request, $throttles)
    {   

        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::user());
        }

        // check if user has any roles
        if(Auth::user()->roles->count() > 0){
            // dd(Auth::user()->roles);
            \Session::put('hasRole', true);
        }
        
        if($request->redirect && !filter_var($request->redirect, FILTER_VALIDATE_URL) ) return redirect($request->redirect);

        return redirect()->intended();
    }



/*
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            $this->loginUsername() => 'required', 'password' => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);


        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();
            if(! $user->verified){
                Auth::logout();

                return redirect($this->loginPath())
                ->withInput($request->only($this->loginUsername(), 'remember'))
                ->withErrors([
                    $this->loginUsername() => 'Please check your email for validation link: ' . $user->email
                ]);

            }else{
                return $this->handleUserWasAuthenticated($request, $throttles);
            }
        }


        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }
*/




    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')
            ->scopes(['scope1', 'scope2'])->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('github')->user();

        // $user->token;
        // OAuth Two Providers
        $token = $user->token;

        // OAuth One Providers
        $token = $user->token;
        $tokenSecret = $user->tokenSecret;

        // All Providers
        $user->getId();
        $user->getNickname();
        $user->getName();
        $user->getEmail();
        $user->getAvatar();
    }
}

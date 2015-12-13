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
    protected $dpBase = 'img/user/'; //should be same as dashboard controller
    protected $dpBaseTiny = 'img/user-tiny/'; //should be same as dashboard controller
    

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getLogout','confrimEmail']]);
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

        //set session to be check for redirections
        \Session::put('newuser_address', true);


        flash("Please check your inbox for the confirmation email.");
        
        return redirect('auth/login');
        
    }



    public function confrimEmail($token){
        $user = User::where('confirmation_code', $token)->firstOrFail();
        $user->verified_email = 1;
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
            'username' => 'required|alpha_dash|max:25|unique:users,username|unique:stores,slug|unique:chains,slug',
            'name' => 'required|max:255',
            'mobile' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'gender' => 'size:1',
            'dob' => 'date',
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

        $user->username = $data['username'];

        $user->name = $data['name'];
        $user->gender = $data['gender'];
        //$user->dob = $data['dob'];
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
        
        if($request->redirect && $request->redirect!='/' && !filter_var($request->redirect, FILTER_VALIDATE_URL) ) return redirect($request->redirect);

        return redirect()->intended('/dashboard/');
    }




    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'login' => 'required', 'password' => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        // $credentials = $this->getCredentials($request);


        // ['username' => $request->username, 'password' => $request->password]
        // dd($credentials);

        if (Auth::attempt(['username' => $request->login, 'password' => $request->password], $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }else if(Auth::attempt(['email' => $request->login, 'password' => $request->password], $request->has('remember'))){
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        /*

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

        */


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






    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')
            ->scopes(['email'])->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request)
    {
        $throttles = $this->isUsingThrottlesLoginsTrait();

        $socialiteUser = Socialite::driver('facebook')->user();

        //check if user exits login
        $user = \App\User::where('provider','facebook')->where('provider_id',$socialiteUser->id)->first();
        if($user != null){
            Auth::login($user);
            
            if($user->username == null){
                flash("Please choose a username to continue!");
                return redirect("/dashboard/general/");
            }else{
                return $this->handleUserWasAuthenticated($request, $throttles);
            }

        }else{ // user does not exists... create new user

            $user = new User;
            
            if($socialiteUser->user['email'] != null){
                $userEmailCheck = \App\User::where('email',$socialiteUser->user['email'])->first();
                if($userEmailCheck != null){
                    //return error page
                    $errors = new \Illuminate\Database\Eloquent\Collection;
                    $errors->add('The Email address assosiated with your facebook account is already registered in our system. Please try to log in with your account or recover your password with "forget your password" option.');
                    return view('errors.general',compact('errors'));
                }
                 $user->email = $socialiteUser->user['email'];
            }

            if($socialiteUser->name != null) $user->name = $socialiteUser->name;
            else if($socialiteUser->nickname != null) $user->name = $socialiteUser->nickname;
            else $user->name = 'FB User';

            if($socialiteUser->user['gender'] == 'female')  $user->gender = 'F';
            else $user->gender = 'M';

            $user->provider = 'facebook';
            $user->provider_id = $socialiteUser->id;

            if($socialiteUser->avatar_original){
                $photoFileName = time() . '-' . str_random(10) . '.jpg' ;

                $image = \Image::make($socialiteUser->avatar_original);
                $image->fit(150,150)->save($this->dpBase.$photoFileName);

                //do again for tiny
                $image = \Image::make($socialiteUser->avatar_original);
                $image->fit(25,25)->save($this->dpBaseTiny.$photoFileName);

                //update db
                $user->dp = $photoFileName;
            }


            $user->save();

            Auth::login($user);

            flash("Please choose a username to continue!");

            //set session to be check for redirections
            \Session::put('newuser_profile', true);
            \Session::put('newuser_address', true);

            return redirect("/dashboard/general/");
        }
    
    }
}

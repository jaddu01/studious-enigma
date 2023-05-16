<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Support\Facades\Password;

use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = 'admin';

    /**
     * ResetPasswordController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }
     protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password|min:6',
        ];
    }
    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'password_confirmation.same'=>'Password and confirm password doesn\'t match'
        ];
    }
    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
             'email','password', 'password_confirmation', 'token'
        );
    }
   
    public function showResetForm(request $request, $token = null)
    {
        //return 'hi';
         if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return view('admin.auth.passwords.reset')->with('token', $token);

        //return view('admin.auth.passwords.reset',compact('token'));
    }

     public function reset(request $request)
    {
            $this->validate($request, [
               'token' => 'required',
               'email' => 'required|email',
               'password' => 'required|confirmed',
           ]);

           $credentials = $request->only(
               'email', 'password', 'password_confirmation', 'token'
           );

           $response = Password::reset($credentials, function ($user, $password) {
               $this->resetPassword($user, $password);
           });

           switch ($response) {
               case Password::PASSWORD_RESET:
                   return redirect($this->redirectPath());

               default:
                   return redirect()->back()
                               ->withInput($request->only('email'))
                               ->withErrors(['email' => trans($response)]);
           }
            
        

     
    }

    
    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
   protected function resetPassword($user, $password)
        {
           $user->password = bcrypt($password);

           $user->save();

           Auth::login($user);
        }

    /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\Response
     */
    protected function sendResetResponse($response)
    {
        return trans($response);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return ['email' => trans($response)];
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    
}

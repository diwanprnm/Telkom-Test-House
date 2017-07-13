<?php

namespace App\Http\Controllers;

use Crypt;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;

class ResetPasswordController extends Controller
{
    // use RedirectsUsers;

    /**
     * Get the name of the guest middleware.
     *
     * @return string
     */
    // protected function guestMiddleware()
    // {
        // $guard = $this->getGuard();

        // return $guard ? 'guest:'.$guard : 'guest';
    // }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmail()
    {
        return $this->showLinkRequestForm();
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        if (property_exists($this, 'linkRequestView')) {
            return view($this->linkRequestView);
        }

        if (view()->exists('client.passwords.email')) {
            return view('client.passwords.email');
        }

        return view('client.password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
		return $this->sendResetLinkEmail($request);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function sendResetLinkEmail(Request $request)
    // {
        // $this->validateSendResetLinkEmail($request);

        // $broker = $this->getBroker();

        // $response = Password::broker($broker)->sendResetLink(
            // $this->getSendResetLinkEmailCredentials($request),
            // $this->resetEmailBuilder()
        // );

        // switch ($response) {
            // case Password::RESET_LINK_SENT:
                // return $this->getSendResetLinkEmailSuccessResponse($response);
            // case Password::INVALID_USER:
            // default:
                // return $this->getSendResetLinkEmailFailureResponse($response);
        // }
    // }
	
	public function sendResetLinkEmail(Request $request)
    {
		$now = strtotime(date('Ymdhis'));
		$time = strtotime("+1 hour",$now);
		$encryptedValue = Crypt::encrypt($time);
		// /* $this->validateSendResetLinkEmail($request); */
		Mail::send('client.emails.password', array('token' => $encryptedValue, 'email' => $request->get('email')), function ($m) use ($request){
        // /* Mail::send('emails.reminder', ['user' => $user], function ($m) use ($user) { */
            $m->to($request->get('email'))->subject('Update Password Web QA!');
        });
		
		return redirect()->back()->with('status', 'Your message has been sent.');
    }

    /**
     * Validate the request of sending reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateSendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    }

    /**
     * Get the needed credentials for sending the reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getSendResetLinkEmailCredentials(Request $request)
    {
        return $request->only('email');
    }

    /**
     * Get the Closure which is used to build the password reset email message.
     *
     * @return \Closure
     */
    protected function resetEmailBuilder()
    {
        return function (Message $message) {
            $message->subject($this->getEmailSubject());
        };
    }

    /**
     * Get the e-mail subject line to be used for the reset link email.
     *
     * @return string
     */
    protected function getEmailSubject()
    {
        return property_exists($this, 'subject') ? $this->subject : 'Your Password Reset Link';
    }

    /**
     * Get the response for after the reset link has been successfully sent.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSendResetLinkEmailSuccessResponse($response)
    {
        return redirect()->back()->with('status', trans($response));
    }

    /**
     * Get the response for after the reset link could not be sent.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSendResetLinkEmailFailureResponse($response)
    {
        return redirect()->back()->withErrors(['email' => trans($response)]);
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset(Request $request, $token = null)
    {
		$now = strtotime(date('Ymdhis'));
		$decrypted = Crypt::decrypt($token);
		$diff_time = ($now - $decrypted);
		if($now < $decrypted){
			return $this->showResetForm($request, $token);			
		}else{
			return $this->getEmail();
		}
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token = null)
    {
		if (is_null($token)) {
            return $this->getEmail();
        }

        $email = $request->input('email');

        if (property_exists($this, 'resetView')) {
            return view($this->resetView)->with(compact('token', 'email'));
        }

        if (view()->exists('client.passwords.reset')) {
            return view('client.passwords.reset')->with(compact('token', 'email'));
        }

        return view('client.reset')->with(compact('token', 'email'));
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postReset(Request $request)
    {
        return $this->reset($request);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
		$email = $request->get('email');
		$password = $request->get('password');
		if($request->input('email') == ''){
			return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'The email field is required.']);
		}
		if($request->input('password') == ''){
			return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['password' => 'The password field is required.']);
		}
		if($request->input('password') == '' || $request->input('password_confirmation') == ''){
			return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['password_confirmation' => 'The password confirmation does not match.']);
		}
		
		$query_update_user = "UPDATE users
			SET 
				password = '".bcrypt($password)."',
				remember_token = '".Str::random(60)."'
			WHERE email = '".$email."'
		";
		$data_update_user = DB::update($query_update_user);
		
		return redirect('/')->with('error_code', 5);
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function getResetValidationRules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * Get the password reset validation messages.
     *
     * @return array
     */
    protected function getResetValidationMessages()
    {
        return [];
    }

    /**
     * Get the password reset validation custom attributes.
     *
     * @return array
     */
    protected function getResetValidationCustomAttributes()
    {
        return [];
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getResetCredentials(Request $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
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
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();

        Auth::guard($this->getGuard())->login($user);
    }

    /**
     * Get the response for after a successful password reset.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetSuccessResponse($response)
    {
		return redirect('/')->with('status', trans($response));
        return redirect($this->redirectPath())->with('status', trans($response));
    }

    /**
     * Get the response for after a failing password reset.
     *
     * @param  Request  $request
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetFailureResponse(Request $request, $response)
    {
		return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return string|null
     */
    public function getBroker()
    {
        return property_exists($this, 'broker') ? $this->broker : null;
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return string|null
     */
    protected function getGuard()
    {
        return property_exists($this, 'guard') ? $this->guard : null;
    }
}

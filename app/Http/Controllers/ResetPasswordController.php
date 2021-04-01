<?php

namespace App\Http\Controllers;

use Crypt;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Mail\Message; 
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;

use App\User;
use App\GeneralSetting;

class ResetPasswordController extends Controller
{

    private const STATUS = 'status';
    private const EMAIL = 'email';
    private const TOKEN = 'token';
    private const PASS_TEXT = 'password';
    private const PASS_CONFIRM_TEXT = 'password_confirmation';
    private const CLIENT_EMAIL_PASS_TEXT = 'client.emails.password';
    private const UPDATE_PASS_QA_TEXT = 'Update Password Web QA!';
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */ 

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
		$email = $request->input(self::EMAIL);
    	if(isset($email)){
    		$email_exists = $this->cekEmail($email);
		 	if($email_exists == 1){ 
    			return $this->sendResetLinkEmail($request);
    		}else{
				return redirect()->back()->with([self::STATUS=>true,'message'=>"Email Doesn't Exists"]);
    		}
    		
    	}else{
			return redirect()->back()->with([self::STATUS=>false,'message'=>"Email Is Required"]);
    	}
    } 
	
	public function sendResetLinkEmail(Request $request)
    {
		$email = $request->input(self::EMAIL);
		$user = User::where(self::EMAIL,'=',''.$email.'')->first();
		
		$now = strtotime(date('Ymdhis'));
		$time = strtotime("+1 hour",$now);
		$encryptedValue = Crypt::encrypt($time); 
        if(GeneralSetting::where('code', 'send_email')->first()['is_active']){
            Mail::send(self::CLIENT_EMAIL_PASS_TEXT, array(self::TOKEN => $encryptedValue, self::EMAIL => $request->get(self::EMAIL)), function ($m) use ($user){ 
                $m->to($user->email)->subject(self::UPDATE_PASS_QA_TEXT);
            });
            
            if($user->email2!=NULL){
                Mail::send(self::CLIENT_EMAIL_PASS_TEXT, array(self::TOKEN => $encryptedValue, self::EMAIL => $request->get(self::EMAIL)), function ($m) use ($user){
                    $m->to($user->email2)->subject(self::UPDATE_PASS_QA_TEXT);
                });
            }
            
            if($user->email3!=NULL){
                Mail::send(self::CLIENT_EMAIL_PASS_TEXT, array(self::TOKEN => $encryptedValue, self::EMAIL => $request->get(self::EMAIL)), function ($m) use ($user){
                    $m->to($user->email3)->subject(self::UPDATE_PASS_QA_TEXT);
                });
            }
        }
		
		return redirect()->back()->with(self::STATUS, $request->get(self::EMAIL));
    }

    /**
     * Validate the request of sending reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateSendResetLinkEmail(Request $request)
    {
        $this->validate($request, [self::EMAIL => 'required|email']);
    }

    /**
     * Get the needed credentials for sending the reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getSendResetLinkEmailCredentials(Request $request)
    {
        return $request->only(self::EMAIL);
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
        return redirect()->back()->with(self::STATUS, trans($response));
    }

    /**
     * Get the response for after the reset link could not be sent.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSendResetLinkEmailFailureResponse($response)
    {
        return redirect()->back()->withErrors([self::EMAIL => trans($response)]);
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
		if($now < $decrypted){
			return $this->showResetForm($request, $token);			
		}else{
			return $this->showLinkRequestForm();
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
           $return_page =  $this->showLinkRequestForm();
        }

        $email = $request->input(self::EMAIL);

        if (property_exists($this, 'resetView')) {
            $return_page =  view($this->resetView)->with(compact(self::TOKEN, self::EMAIL));
        }

        if (view()->exists('client.passwords.reset')) {
            $return_page = view('client.passwords.reset')->with(compact(self::TOKEN, self::EMAIL));
        }

        return $return_page;
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
		$email = $request->get(self::EMAIL);
		$password = $request->get(self::PASS_TEXT);
        $return_page =  redirect('/login')->with('send_new_password', 5);
		if($request->input(self::EMAIL) == ''){
			$return_page =  redirect()->back()
            ->withInput($request->only(self::EMAIL))
            ->withErrors([self::EMAIL => 'The email field is required.']);
		}
		if($request->input(self::PASS_TEXT) == ''){
			$return_page =   redirect()->back()
            ->withInput($request->only(self::EMAIL))
            ->withErrors([self::PASS_TEXT => 'The password field is required.']);
		}
		if($request->input(self::PASS_TEXT) != $request->input(self::PASS_CONFIRM_TEXT)){
			$return_page =   redirect()->back()
            ->withInput($request->only(self::EMAIL))
            ->withErrors([self::PASS_CONFIRM_TEXT => 'The password confirmation does not match.']);
		}
		
		$query_update_user = "UPDATE users
			SET 
				password = '".bcrypt($password)."',
				remember_token = '".Str::random(60)."'
			WHERE email = '".$email."'
		";
		DB::update($query_update_user);

        return $return_page;
		
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
            self::EMAIL, self::PASS_TEXT, self::PASS_CONFIRM_TEXT, self::TOKEN
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
            self::PASS_TEXT => bcrypt($password),
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
		return redirect('/')->with(self::STATUS, trans($response)); 
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
            ->withInput($request->only(self::EMAIL))
            ->withErrors([self::EMAIL => trans($response)]);
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
	
	function cekEmail($email)
    {
		$user = User::where(self::EMAIL,'=',''.$email.'')->get();
		return count($user);
    }
}

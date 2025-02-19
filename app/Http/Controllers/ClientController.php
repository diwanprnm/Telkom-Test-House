<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Alumnus;
use App\Role;
use App\User;
use App\UserSchool;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use Hash;
use Auth;
use Cart;
use App\Logs;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use App\Services\Logs\LogService;

/**
* Class AuthenticationController
*
* AuthenticateController adalah controller yang digunakan untuk me-manage
* proses autentikasi user ke aplikasi koloni.
* AuthenticateController inheritance terhadap class Controller.
* 
* 
* @author Nurwin Hermansyah
* @since version 1.0
* 
*/
class ClientController extends Controller
{	
	protected $loginPath = '/client/login';  
	private const ERROR_CODE = 'error_code';
	private const EMAIL = 'email';
	private const KATA_KUNCI = 'password';   


	public function cekLogin(Request $request)
    {
        $currentUser = Auth::user();
		if ($currentUser){
            return 1;
        }else{
			return 0;
		}
    }
		
	public function logout(Request $request) {
		$currentUser = Auth::user();
		if ($currentUser){
			
			$logService = new LogService();  
            $logService->createLog('Logout',"LOGOUT");

			Auth::logout();
        }
        Cart::destroy();
		return redirect('/');
	}
	
	/**
	* Fungsi untuk melakukan autentikasi user dengan email dan password
	* jika sukses akan memberikan token dengan format JWT 
	*
	* @author Nurwin Hermansyah
	* @since version 1.0
	*
	* @param Request $request Request object user_email dan user_password
	* @return JSON Returns JSON object status dan data token
	*/
	public function authenticate(Request $request)
	{
		/*START cekDeletedEmail*/
		$query = DB::table('users')
		->join('companies', function ($join) use ($request){
			$join->on('users.company_id', '=', 'companies.id')
				->where('users.email','=',''.$request->input(self::EMAIL).'');
		});
		$query->where(function($q){
			$q->where('users.is_deleted', '=' , 1)
				->orWhere('users.is_active', '=' , 0);
		});
		$query->orWhere(function($q){
			$q->where('companies.is_active', '=' , 0);
		});
		$user = $query->get();
		/*END cekDeletedEmail*/

		if(count($user) == 0){
			$credentials = $request->only(self::EMAIL, self::KATA_KUNCI);
			if (Auth::attempt($credentials)) {
				$logService = new LogService();
				$logService->createLog('Login', 'LOGIN', '' );
				return redirect()->intended(session()->get('intendedUrl'));
			}else{
				return back()->with(self::ERROR_CODE, 5)
				->with(self::ERROR_CODE, 5)
				->withInput($request->all())
				->withErrors('Email or Password Not Match');
			}
		}else{
			return back()->with(self::ERROR_CODE, 5)
			->with(self::ERROR_CODE, 5)
			->withInput($request->all())
			->withErrors('User not found or User Banned by admin');
		}

	}
}


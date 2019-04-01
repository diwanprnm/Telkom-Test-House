<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $email_deleted = $this->cekDeleted($request->input('email'));
        if($email_deleted == 0){
            if (Auth::guard($guard)->guest()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response('Unauthorized.', 401);
                } else {
                    return redirect()->guest('admin/login');
                    // return redirect()->back();
                }
            }

            return $next($request);
        }else{
            return back()->with('errors', 'email')
            ->withInput($request->all());
            // ->withErrors('User not found or User Banned by admin');;
        }
    }

    function cekDeleted($email)
    {
        $query = DB::table('users')
        ->join('companies', function ($join) use ($email){
            $join->on('users.company_id', '=', 'companies.id')
                 ->where('users.email','=',''.$email.'');
        });
        $query->where(function($q){
            $q->where('users.is_deleted', '=' , 1)
                ->orWhere('users.is_active', '=' , 0);
        });
        $query->orWhere(function($q){
            $q->where('companies.is_active', '=' , 0);
        });
        $user = $query->get();
        // $user = $query->toSql();
        // dd($user);exit;
        
        return count($user);
    }
}

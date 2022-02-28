<?php

namespace App\Http\Middleware;

use Closure;

class TempPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$have)
    {
        if(!$request->user())//ako nije ulogovan
        return abort(403);//napravi neku stranicu tipa nemate pristup
        if($have=="true")//ako se trazi provera da korisnik mora da ima temp password(mora ovako izgleda da parsuje kao string a ne bool)
        {
            if($request->user()->temp_pass!=null)//ako ima temp_password
            {
                return $next($request);
            }   
            else
            { 
                return abort(403);//napravi neku stranicu tipa nemate pristup
            }
        }
        else//ako se trazi da korsinik nema temp password
        {
            
            if($request->user()->temp_pass==null)//ako nema temp_password
            {
                return $next($request);
            }   
            else
            { 
                return abort(403);//napravi neku stranicu tipa nemate pristup
            }
        }
       
    }
}

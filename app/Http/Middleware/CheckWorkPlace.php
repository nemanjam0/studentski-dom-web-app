<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class CheckWorkPlace
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$dormid)
    {
        if(!$request->user())//ako nije ulogovan
        return abort(403);//napravi neku stranicu tipa nemate pristup
            if($request->user()->worksInDorm($dormid))
            return $next($request);
        return abort(403);//napravi neku stranicu tipa nemate pristup
    }
    //x
       /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
}

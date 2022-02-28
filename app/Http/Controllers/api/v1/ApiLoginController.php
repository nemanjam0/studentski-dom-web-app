<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiLoginController extends Controller
{
    public function login(Request $request)
    {
        $login=$request->validate([
            'email'=>'required|string',
            'password'=>'required|string',
        ]);
        if(!Auth::attempt($login))
        return response(['message'=>'Invalid login credentials'],401);
        $scopes=[];
        $authuser=Auth::user();
        if($authuser->user_type=='admin' || $authuser->user_type=='cafeteria_worker' || $authuser->user_type=='atm')
        array_push($scopes,'get-card-info');
        if($authuser->user_type=='admin' || $authuser->user_type=='cafeteria_worker')
        array_push($scopes,'create-cafeteria-card-transaction');
        if($authuser->user_type=='admin' || $authuser->user_type=='atm')
        array_push($scopes,'create-atm-card-transaction');
        $accessToken=Auth::user()->createToken('authToken',$scopes)->accessToken;
        //dodaj da brise sve accesstokene koji su vec kreirani(ne moze jedan korisnik na dva mesta da bude ulogovan!)
        $authuser->setAttribute('accessToken', $accessToken);
        return response($authuser->only('name','surname','accessToken'));
    }
}

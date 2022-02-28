<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('auth.passwords.change');
    }
    public function store(Request $request)
    {     $user_id=auth()->user()->id;
          $user=User::find($user_id); 
          $this->validate($request,[
            'old_password' => function ($attribute, $value, $fail) use($request) {
                if (! Hash::check($value, $request->user()->password)) {
                    $fail('Your current password does not match');
                }
            },
            'password' => ['required', 'string', 'min:8', 'confirmed',function ($attribute, $value, $fail) use($request) {
                if (Hash::check($value, $request->user()->password)) {
                    $fail('Your password is same as old one.');
                }
            }],
            ]);
            $user->password=Hash::make($request->input('password'));
            $user->temp_pass=null;
            $user->save();
            return redirect('dashboard')->with('success','Password changed');
         
    }
}

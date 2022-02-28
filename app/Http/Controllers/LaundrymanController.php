<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;

class LaundrymanController extends Controller
{
    public function __construct()
    {
        
       $this->middleware(['CheckRole:admin,laundryman']);//samo admin moze da pristupi ovom delu
    }
    public function student_borrowing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator,'student_borrowing')
                        ->withInput();
        }
        $id=User::get_id_from_email($request->input('email'));
       return redirect()->route('loaneditems.borrowing',$id);
    }
    public function student_returning(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator,'student_returning')
                        ->withInput();
        }
        $id=User::get_id_from_email($request->input('email'));
       return redirect()->route('loaneditems.returning',$id);
    }
    public function student_items(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator,'student_items')
                        ->withInput();
        }
        $id=User::get_id_from_email($request->input('email'));
       return redirect()->route('loaneditems.studentsitems',$id);
    }
}

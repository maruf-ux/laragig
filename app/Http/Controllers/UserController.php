<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create()
    {
        return view('website.users.register');
    }


    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required | email',
            'password' => 'required | confirmed ',
        ]);
        $validate['password'] = bcrypt($validate['password']);

        $user = User::create($validate);

        auth()->login($user);
        return redirect('/')->with('message', 'User created and logged in ');

    }


    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out');
    }



    public function login()
    {
        return view('website.users.login');
    }

    public function authenticate(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required | email',
            'password' => 'required  '
        ]);

        if(auth()->attempt($validate)){
            $request->session()->regenerate();
            return redirect('/')->with('message','You are Logged in');
        }

        return back()->withErrors(['email' =>'Invalid Credentials'])->onlyInput('email');
    }
}

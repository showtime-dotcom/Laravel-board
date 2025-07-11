<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered; // ★追加：Registeredイベントを使用するため
use Illuminate\Support\Facades\Auth; // ★追加：Authファサードを使用するため (必要に応じて)

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * RegistersUsersトレイトのregisterメソッドをオーバーライドします。
     * ここでユーザーを自動ログインさせずに、ログインページにリダイレクトします。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request) // ★このメソッドを追加または修正★
    {
        $this->validator($request->all())->validate(); // バリデーションを実行

        event(new Registered($user = $this->create($request->all()))); // ユーザーを作成し、Registeredイベントを発火

        // ★★★ ここが重要 ★★★
        // デフォルトのRegistersUsersトレイトはここで Auth::guard()->login($user); を呼び出しますが、
        // それを削除またはコメントアウトすることで、自動ログインを無効にします。
        // $this->guard()->login($user); // この行は削除またはコメントアウトしてください

        // ログインページへリダイレクト
        return redirect()->route('login')->with('status', '登録が完了しました。ログインしてください。');
    }
}

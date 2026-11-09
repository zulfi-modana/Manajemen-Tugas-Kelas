
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Models\User;

// ========================
// WEB ROUTES
// ========================

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// Web Login
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($credentials)) {
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    $request->session()->regenerate();

    return redirect('/dashboard');
});

// Web Register
Route::post('/register', function (Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'role' => 'required|in:teacher,student',
    ]);

    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'role' => $data['role'],
    ]);

    Auth::login($user);
    $request->session()->regenerate();

    return redirect('/dashboard');
});

// Web Logout
Route::post('/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->middleware('auth');


// ========================
// API ROUTES
// ========================

Route::prefix('api')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::middleware('role:teacher')->group(function () {
            Route::post('/tasks', [TaskController::class, 'store']);
            Route::put('/tasks/{task}', [TaskController::class, 'update']);
            Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
        });

        Route::middleware('role:student')->group(function () {
            Route::post('/tasks/{task}/complete', [TaskController::class, 'complete']);
        });

        Route::get('/tasks', [TaskController::class, 'index']);
    });
});

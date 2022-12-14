<?php

namespace App\Http\Controllers\Auth;

use App\Auth\Concerns\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    use AuthenticatesUsers;

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->guard()->logout();

        $this->sellersGuard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return redirect()->action([self::class, 'create']);
    }

    public function attemptLogin(LoginRequest $request)
    {
        $status = $this->guard()->attempt(
            $request->only(['email', 'password']),
            $request->boolean('remember')
        );

        if (! $status) {
            return $this->sellersGuard()->attempt(
                $request->only(['email', 'password']),
                $request->boolean('remember')
            );
        }

        return $status;
    }

    protected function guard(): StatefulGuard
    {
        return Auth::guard('users');
    }

    protected function sellersGuard(): StatefulGuard
    {
        return Auth::guard('sellers');
    }

    public function redirectTo(): string
    {
        return action([DashboardController::class, 'index']);
    }
}

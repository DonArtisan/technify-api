<?php

namespace App\Http\Controllers\Auth;

use App\Auth\Concerns\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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
        $this->guard(Session::get($this->guardIdentifier))->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::forget($this->guardIdentifier);

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return redirect()->action([self::class, 'create']);
    }

    public function attemptLogin(LoginRequest $request)
    {
        Session::put($this->guardIdentifier, 'users');

        $email = $request->input('email');

        $status = $this->guard()->attempt(['password' => $request->input('password'), function (Builder $query) use ($email) {
            $query->whereHas('person', function ($query) use ($email) {
                $query->where('email', $email);
            });
        }]);

        if (! $status) {
            Session::put($this->guardIdentifier, 'sellers');

            return $this->guard('sellers')->attempt(
                $request->only(['email', 'password']),
                $request->boolean('remember')
            );
        }

        return $status;
    }

    protected function guard(string $guard = 'users'): StatefulGuard
    {
        return Auth::guard($guard);
    }

    public function redirectTo(): string
    {
        return action([DashboardController::class, 'index']);
    }
}

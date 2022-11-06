<?php

declare(strict_types=1);

namespace App\Auth\Concerns;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait AuthenticatesUsers
{
    abstract public function create(): View;

    public function store(Request $request): RedirectResponse
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

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return redirect('/');
    }

    protected function validateLogin(Request $request): void
    {
        $request->validate([
            'email' => [
                'required',
                'email:rfc',
            ],
            'password' => [
                'required',
            ],
        ]);
    }

    public function attemptLogin(Request $request): bool
    {
        return $this->guard()->attempt(
            $request->only(['email', 'password']),
            $request->boolean('remember')
        );
    }

    protected function sendLoginResponse(Request $request): RedirectResponse
    {
        $request->session()->regenerate();

        // @phpstan-ignore-next-line we know that user is the correct type.
        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return redirect()->intended($this->redirectTo());
    }

    abstract protected function guard(): StatefulGuard;

    abstract public function redirectTo(): string;

    protected function authenticated(Request $request, Authenticatable $user): null|RedirectResponse
    {
        return null;
    }

    protected function loggedOut(Request $request): null|RedirectResponse
    {
        return null;
    }
}

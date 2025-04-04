<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {
    }

    public function registerIndex(): Response
    {
        return Inertia::render('Register/Index');
    }

    public function loginIndex(): Response
    {
        return Inertia::render('Login/Index');
    }

    /**
     * @throws \Exception
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (!$this->authService->register($data)) {
            return back()->withErrors([
                'email' => 'Something went wrong.',
            ]);
        }

        return redirect()->route('home');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (!$this->authService->login($data)) {
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ]);
        }

        return redirect()->route('home');
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('index');
    }
}

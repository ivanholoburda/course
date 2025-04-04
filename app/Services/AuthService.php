<?php

namespace App\Services;

use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;

readonly class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function register(array $data): ?User
    {
        /** @var User|null $user */
        $user = $this->userRepository->create($data);
        auth()->login($user);

        return $user;
    }

    public function login(array $data): bool
    {
        if (!Auth::attempt($data)) {
            return false;
        }

        session()->regenerate();

        return true;
    }

    public function logout(): bool
    {
        session()->invalidate();
        session()->regenerateToken();
        Auth::logout();

        return true;
    }
}

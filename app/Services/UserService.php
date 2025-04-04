<?php

namespace App\Services;

use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Http\UploadedFile;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function update(User $user, array $data): ?User
    {
        /** @var User|null */
        return $this->userRepository->update($user, $data);
    }

    /**
     * @throws \Exception
     */
    public function uploadAvatar(User $user, UploadedFile $file): bool
    {
        $path = $file->store('avatars', 'public');
        return (bool) $this->userRepository
            ->update($user, [
                'avatar' => $path
            ]);
    }
}

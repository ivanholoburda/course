<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UploadAvatarRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProfileController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function index(): Response
    {
        /** @var User $user */
        $user = auth()->user();

        return Inertia::render('Profile/Index', [
            'user' => new UserResource($user),
        ], );
    }

    /**
     * @throws \Exception
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $data = $request->validated();

        if (!$this->userService->update($user, $data)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile. Check data and try again.',
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
        ]);
    }

    /**
     * @throws \Exception
     */
    public function uploadAvatar(UploadAvatarRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$this->userService->uploadAvatar($user, $request->file('avatar'))) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload avatar. Check data and try again.',
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
        ]);
    }
}

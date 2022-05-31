<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Core\User\UserService;
use App\Services\Domain\User\Exceptions\InvalidTokenException;
use App\Services\Domain\User\VerifyUserService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class VerifyController extends Controller
{
    private VerifyUserService $verifyUserService;
    private UserService $userService;

    public function __construct(
        VerifyUserService $verifyUserService,
        UserService $userService
    ) {
        $this->verifyUserService = $verifyUserService;
        $this->userService       = $userService;
    }

    public function __invoke(int $id, string $token)
    {
        try {
            $user = $this->userService->findById($id);
            if ( ! $user instanceof User) {
                die(Response::HTTP_NOT_FOUND);
            }

            $this->verifyUserService->verify($user, $token);

            return redirect()
                ->route('login-page')
                ->with('success', 'Email Address verified successfully, please login.');
        } catch (InvalidTokenException $e) {
            return redirect()
                ->route('login-page')
                ->with('error', 'Invalid token!');
        } catch (Throwable $e) {
            Log::error('failed to verify email address', [
                'error_message' => $e->getMessage(),
                'error_trace'   => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('login-page')
                ->with('error', 'Error occurred, please retry later!');
        }
    }
}
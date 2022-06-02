<?php

namespace App\Http\Controllers\Client\Subscribe;

use App\Http\Controllers\Controller;
use App\Models\User;

class ShowSubscriptionPageController extends Controller
{
    public function __invoke()
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->subscribed()) {
            return redirect()
                ->route('dashboard.home')
                ->with('error', 'You are already subscribed!');
        }

        return view('client.subscribe.show')
            ->with('intent', $user->createSetupIntent());
    }
}

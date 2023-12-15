<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Subscribe\NewsletterRequest;
use App\Models\Subscriber;
use App\Jobs\SubscriberJoinJob;
use Illuminate\Support\Str;

class SubscribeController extends Controller
{
    public function store(NewsletterRequest $request)
    {
        try {
            $Subscriber = Subscriber::create([
                'email' => $request->email,
                'token_hash' => Str::random(64)
            ]);

            SubscriberJoinJob::dispatch($Subscriber);

            return responseJSON($Subscriber, 200, 'You have successfully subscribed. Please check your email spam folder.');
        } catch (\Exception $e) {
            return responseJSON([], 500, $e->getMessage());
        }
    }

    public function show(string $hash)
    {
        try {
            $subscriber = Subscriber::where('token_hash', $hash)->firstOrFail();

            $subscriber->update([
                'token_hash' => null,
                'verified_at' => now()
            ]);

            return responseJSON($subscriber, 200, 'You have successfully verified your email.');
        } catch (\Exception $e) {
            return responseJSON([], 500, $e->getMessage());
        }
    }
}

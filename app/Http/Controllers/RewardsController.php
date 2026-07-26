<?php

namespace App\Http\Controllers;

class RewardsController extends Controller
{
    public function index()
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isCustomer(), 403);

        $user = auth()->user();
        $tiers = config('loyalty.tiers');
        $currentTier = $user->currentTier();

        $nextTier = match ($currentTier) {
            'bronze' => 'silver',
            'silver' => 'gold',
            default => null,
        };

        $nextTierThreshold = $nextTier ? $tiers[$nextTier] : null;
        $currentTierThreshold = $tiers[$currentTier];

        if ($nextTierThreshold !== null) {
            $tierProgressPercent = (int) min(100, max(0, round(
                (($user->lifetime_points - $currentTierThreshold) / ($nextTierThreshold - $currentTierThreshold)) * 100
            )));
        } else {
            $tierProgressPercent = 100;
        }

        $transactions = $user->loyaltyTransactions()
            ->with('rental')
            ->latest()
            ->paginate(10);

        return view('customer.rewards.index', [
            'loyaltyPoints' => $user->loyalty_points,
            'lifetimePoints' => $user->lifetime_points,
            'currentTier' => $currentTier,
            'nextTier' => $nextTier,
            'nextTierThreshold' => $nextTierThreshold,
            'tierProgressPercent' => $tierProgressPercent,
            'transactions' => $transactions,
        ]);
    }
}

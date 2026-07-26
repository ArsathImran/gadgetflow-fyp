<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                {{ __('Rewards') }}
            </h2>
            <p class="font-body text-sm text-slate">Track your loyalty points, tier, and redemption history.</p>
        </div>
    </x-slot>

    @php
        $tierStyles = [
            'bronze' => ['badge' => 'bg-amber-100 text-amber-800 border-amber-200', 'bar' => 'bg-amber-500', 'card' => 'from-amber-50 to-white border-amber-200'],
            'silver' => ['badge' => 'bg-slate-200 text-slate-700 border-slate-300', 'bar' => 'bg-slate-500', 'card' => 'from-slate-100 to-white border-slate-200'],
            'gold' => ['badge' => 'bg-yellow-100 text-yellow-800 border-yellow-300', 'bar' => 'bg-yellow-500', 'card' => 'from-yellow-50 to-white border-yellow-200'],
        ];
        $style = $tierStyles[$currentTier];
    @endphp

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,1fr)]">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)] sm:p-8">
                        <p class="font-body text-sm font-medium text-slate">Points Balance</p>
                        <p class="mt-3 font-display text-5xl font-bold text-ink">{{ number_format($loyaltyPoints) }}</p>
                        <p class="mt-2 font-body text-sm text-slate">
                            Worth <x-spec-chip>{{ number_format(auth()->user()->pointsValueInCurrency($loyaltyPoints), 2) }}</x-spec-chip> in discounts
                        </p>

                        <div class="mt-6 flex items-center gap-3">
                            <span class="inline-flex items-center rounded-full border px-3 py-1 font-body text-sm font-semibold {{ $style['badge'] }}">
                                {{ ucfirst($currentTier) }} Tier
                            </span>
                            <span class="font-mono text-xs text-slate">{{ number_format($lifetimePoints) }} lifetime pts</span>
                        </div>

                        <div class="mt-6">
                            @if ($nextTier)
                                <div class="flex items-center justify-between font-body text-xs text-slate">
                                    <span>{{ ucfirst($currentTier) }}</span>
                                    <span>{{ ucfirst($nextTier) }} at {{ number_format($nextTierThreshold) }} pts</span>
                                </div>
                                <div class="mt-2 h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full {{ $style['bar'] }}" style="width: {{ $tierProgressPercent }}%"></div>
                                </div>
                                <p class="mt-2 font-body text-xs text-slate">
                                    {{ max(0, $nextTierThreshold - $lifetimePoints) }} more lifetime points to reach {{ ucfirst($nextTier) }}.
                                </p>
                            @else
                                <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full {{ $style['bar'] }}" style="width: 100%"></div>
                                </div>
                                <p class="mt-2 font-body text-xs text-slate">You've reached the highest tier. Thanks for being a loyal customer!</p>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-3xl border {{ $style['card'] }} bg-gradient-to-br p-6 shadow-[0_18px_40px_rgba(15,23,42,0.06)] sm:p-8">
                        <h3 class="font-display text-lg font-semibold text-ink">How Points Work</h3>
                        <ul class="mt-4 space-y-3 font-body text-sm text-slate-700">
                            <li>Earn {{ (int) config('loyalty.points_per_currency_unit') }} point(s) for every RM1 spent on a completed rental.</li>
                            <li>Redeem points at checkout — each point is worth RM{{ number_format((float) config('loyalty.redemption_rate'), 2) }} off your total.</li>
                            <li>Points are refunded automatically if a redeemed rental request is rejected or cancelled.</li>
                            <li>Tiers are based on lifetime points earned and never decrease.</li>
                        </ul>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)] sm:p-8">
                    <h3 class="font-display text-lg font-semibold text-ink">Transaction History</h3>
                    <p class="mt-1 font-body text-sm text-slate">A record of points earned, redeemed, and refunded.</p>

                    @if ($transactions->count())
                        <div class="mt-6 overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-cloud">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Date</th>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Description</th>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate">Type</th>
                                        <th class="px-6 py-3 text-right font-body text-xs font-semibold uppercase tracking-wider text-slate">Points</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white">
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td class="px-6 py-4 font-body text-sm text-slate-600">
                                                {{ $transaction->created_at->format('Y-m-d H:i') }}
                                            </td>
                                            <td class="px-6 py-4 font-body text-sm text-ink">
                                                {{ $transaction->description }}
                                                @if ($transaction->rental_id)
                                                    <a href="{{ route('customer.rentals.show', $transaction->rental_id) }}" class="ml-1 font-body text-xs font-semibold text-indigo-700 hover:text-indigo-500">
                                                        View Rental
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 font-body text-xs font-semibold
                                                    @if ($transaction->type === 'earned') bg-green-100 text-green-800
                                                    @elseif ($transaction->type === 'redeemed') bg-indigo-100 text-indigo-800
                                                    @else bg-amber-100 text-amber-800 @endif">
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm">
                                                <x-spec-chip class="{{ $transaction->points >= 0 ? 'text-green-700' : 'text-rose-700' }}">
                                                    {{ $transaction->points >= 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                                                </x-spec-chip>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="mt-6 rounded-2xl border border-dashed border-gray-300 px-6 py-12 text-center">
                            <p class="font-display text-base font-semibold text-ink">No points activity yet.</p>
                            <p class="mt-2 font-body text-sm text-gray-500">Complete a rental to start earning loyalty points.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

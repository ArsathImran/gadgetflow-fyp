@props(['status', 'pickupType' => null, 'collectedLabel' => 'Collected'])

@php
    $label = match (true) {
        $status === 'pending_collection' && $pickupType === 'walk_in' => 'Pending Collection',
        $status === 'collected' && $pickupType === 'walk_in' => $collectedLabel,
        default => ucwords(str_replace('_', ' ', $status)),
    };

    $style = match (true) {
        in_array($status, ['verified', 'collected'], true) => 'bg-green-100 text-green-800',
        $status === 'partially_verified' => 'bg-amber-100 text-amber-800',
        $status === 'rejected' => 'bg-red-100 text-red-800',
        in_array($status, ['pending', 'pending_collection'], true) => 'bg-yellow-100 text-yellow-800',
        default => 'bg-gray-100 text-gray-800',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex rounded-full px-2.5 py-0.5 font-body text-xs font-semibold {$style}"]) }}>
    {{ $label }}
</span>

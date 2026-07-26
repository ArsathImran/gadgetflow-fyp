@props(['status'])

@php
    $labels = [
        'not_applicable' => 'Not Applicable',
        'waiting_for_shipping' => 'Waiting for Shipping',
        'shipped' => 'Shipped',
        'out_for_delivery' => 'Out for Delivery',
        'delivered' => 'Delivered',
    ];

    $styles = [
        'not_applicable' => 'bg-gray-100 text-gray-500',
        'waiting_for_shipping' => 'bg-slate-100 text-slate-700',
        'shipped' => 'bg-indigo-100 text-indigo-800',
        'out_for_delivery' => 'bg-cyan-100 text-cyan-800',
        'delivered' => 'bg-green-100 text-green-800',
    ];

    $label = $labels[$status] ?? ucwords(str_replace('_', ' ', $status));
    $style = $styles[$status] ?? 'bg-gray-100 text-gray-500';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex rounded-full px-2.5 py-0.5 font-body text-xs font-semibold {$style}"]) }}>
    {{ $label }}
</span>

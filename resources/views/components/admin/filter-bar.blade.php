@props(['action' => null])

<form method="GET" action="{{ $action ?? url()->current() }}" {{ $attributes->merge(['class' => 'admin-filter-bar']) }}>
    {{ $slot }}
    <div class="flex gap-2 pb-0.5">
        <button type="submit" class="btn-primary btn-sm">Apply</button>
        @if(request()->query())
        <a href="{{ $action ?? url()->current() }}" class="btn-ghost btn-sm">Clear</a>
        @endif
    </div>
</form>

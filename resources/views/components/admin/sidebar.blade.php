@php
$isSuperAdmin = auth()->user()->isSuperAdmin();
$isTerminalAdmin = auth()->user()->isTerminalAdmin();
$currentRoute = request()->route()?->getName() ?? '';

$isNavActive = function (array $item) use ($currentRoute): bool {
    if ($currentRoute === '') {
        return false;
    }

    if (! empty($item['exact'])) {
        return $currentRoute === $item['exact'];
    }

    if (! empty($item['prefix'])) {
        return str_starts_with($currentRoute, $item['prefix']);
    }

    return $currentRoute === ($item['route'] ?? '');
};

$superAdminNav = [
    ['section' => 'Platform'],
    ['route' => 'admin.dashboard', 'exact' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['route' => 'admin.cities.index', 'prefix' => 'admin.cities.', 'label' => 'Cities', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
    ['route' => 'admin.terminals.index', 'prefix' => 'admin.terminals.', 'label' => 'Terminals / Adda', 'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
    ['route' => 'admin.bus-stands.index', 'prefix' => 'admin.bus-stands.', 'label' => 'Bus Stands', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
    ['route' => 'admin.routes.index', 'prefix' => 'admin.routes.', 'label' => 'Routes', 'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
];

$terminalAdminNav = [
    ['section' => 'Terminal'],
    ['route' => 'admin.dashboard', 'exact' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['route' => 'admin.terminals.my', 'prefix' => 'admin.terminals.', 'label' => 'My Terminal / Adda', 'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
    ['section' => 'Stands'],
    ['route' => 'admin.bus-stands.index', 'prefix' => 'admin.bus-stands.', 'label' => 'Bus Stands', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
    ['route' => 'admin.terminal-users.index', 'prefix' => 'admin.terminal-users.', 'label' => 'Stand Users', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1'],
    ['section' => 'Operations'],
    ['route' => 'admin.routes.index', 'prefix' => 'admin.routes.', 'label' => 'Routes', 'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
];

$busStandAdminNav = [
    ['section' => 'Overview'],
    ['route' => 'admin.dashboard', 'exact' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['section' => 'Stand'],
    ['route' => 'admin.bus-stands.my', 'prefix' => 'admin.bus-stands.', 'label' => 'My Bus Stands', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
    ['section' => 'Fleet'],
    ['route' => 'admin.vehicles.index', 'prefix' => 'admin.vehicles.', 'label' => 'Vehicles', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
    ['route' => 'admin.drivers.index', 'prefix' => 'admin.drivers.', 'label' => 'Drivers', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    ['route' => 'admin.routes.index', 'prefix' => 'admin.routes.', 'label' => 'Routes', 'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
    ['route' => 'admin.schedules.index', 'prefix' => 'admin.schedules.', 'label' => 'Schedules', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
    ['section' => 'Sales'],
    ['route' => 'admin.bookings.index', 'prefix' => 'admin.bookings.', 'label' => 'Bookings', 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
];

$nav = match (true) {
    $isSuperAdmin => $superAdminNav,
    $isTerminalAdmin => $terminalAdminNav,
    default => $busStandAdminNav,
};

$panelTitle = match (true) {
    $isSuperAdmin => 'BSS Platform',
    $isTerminalAdmin => 'BSS Terminal',
    default => 'BSS Operations',
};

$panelTag = match (true) {
    $isSuperAdmin => 'Super Admin',
    $isTerminalAdmin => 'Terminal Admin',
    default => 'Stand Admin',
};
@endphp

<aside
    class="admin-sidebar fixed inset-y-0 left-0 z-50 flex h-screen max-h-screen w-[17.5rem] flex-col transition-transform duration-200 -translate-x-full lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : ''"
>
    <div class="admin-sidebar-brand">
        <div class="admin-sidebar-logo">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16c0 .88.39 1.67 1 2.22V20a1 1 0 001 1h1a1 1 0 001-1v-1h8v1a1 1 0 001 1h1a1 1 0 001-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-2.21-1.79-4-4-4H8C5.79 2 4 3.79 4 6v10z"/></svg>
        </div>
        <div class="min-w-0">
            <p class="admin-sidebar-tag">{{ $panelTag }}</p>
            <p class="admin-sidebar-title truncate">{{ $panelTitle }}</p>
        </div>
    </div>

    @if($isTerminalAdmin)
    <div class="px-1 pt-3">
        @php $terminal = auth()->user()->primaryTerminal(); @endphp
        @if($terminal)
        <div class="rounded-xl border border-violet-200/80 bg-violet-50/90 px-4 py-3 dark:border-violet-800/50 dark:bg-violet-950/40">
            <p class="text-[10px] font-bold uppercase tracking-wider text-violet-600 dark:text-violet-400">Your terminal</p>
            <p class="mt-1 truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $terminal->name }}</p>
            <p class="text-xs text-slate-500">{{ $terminal->city }}</p>
        </div>
        @endif
    </div>
    @elseif(!$isSuperAdmin)
    <div class="px-1 pt-3">
        <x-admin.stand-card />
    </div>
    @endif

    <nav class="flex-1 space-y-0.5 overflow-y-auto p-3" aria-label="Admin navigation">
        @foreach($nav as $item)
            @if(isset($item['section']))
            <p class="admin-nav-section">{{ $item['section'] }}</p>
            @else
            @php $active = $isNavActive($item); @endphp
            <a
                href="{{ route($item['route']) }}"
                @if($active) aria-current="page" @endif
                class="{{ $active ? 'admin-nav-item-active' : 'admin-nav-item' }}"
            >
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
            @endif
        @endforeach
    </nav>

    <div class="admin-sidebar-footer">
        <a href="{{ route('home') }}" class="admin-nav-item text-sm">← Public site</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="admin-nav-item w-full text-left text-sm text-danger-600 dark:text-danger-400">Sign out</button>
        </form>
    </div>
</aside>

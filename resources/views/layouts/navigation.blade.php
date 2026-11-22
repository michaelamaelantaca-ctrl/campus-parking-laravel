<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    @php
        $adminDailyReportsUrl = \Illuminate\Support\Facades\Route::has('admin.reports.daily')
            ? route('admin.reports.daily')
            : url('/admin/reports/daily');
    @endphp

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ Auth::check() && Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="flex shrink-0 items-center gap-2.5">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-blue-600 p-2 text-white shadow-sm">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 21V4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v17M8 7h8M8 11h8M8 15h5M3 21h18"/><path d="m7 21 2-4h6l2 4"/></svg>
                    </span>
                    <span class="text-[15px] font-bold text-slate-900">Campus Parking</span>
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <span class="hidden rounded-md bg-slate-100 px-2 py-1 text-[9px] font-extrabold tracking-widest text-slate-600 sm:inline">ADMIN</span>
                    @elseif(Auth::check() && Auth::user()->role === 'guard')
                        <span class="hidden rounded-md bg-blue-50 px-2 py-1 text-[9px] font-extrabold tracking-widest text-blue-700 sm:inline">GUARD</span>
                    @endif
                </a>

                @if(Auth::check() && Auth::user()->role === 'admin')
                    <div class="hidden h-16 items-center gap-1 md:flex">
                        <a href="{{ route('admin.dashboard') }}" class="flex h-16 items-center px-3 text-sm font-semibold {{ request()->routeIs('admin.dashboard') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-blue-600' }}">Dashboard</a>
                        <a href="{{ route('users.index') }}" class="flex h-16 items-center px-3 text-sm font-semibold {{ request()->routeIs('users.*') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-blue-600' }}">User Management</a>
                        <a href="{{ route('admin.zones.index') }}" class="flex h-16 items-center px-3 text-sm font-semibold {{ request()->routeIs('admin.zones.*') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-blue-600' }}">Parking Zones</a>
                        <a href="{{ route('admin.vehicle-logs.index') }}" class="flex h-16 items-center px-3 text-sm font-semibold {{ request()->routeIs('admin.vehicle-logs.*') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-blue-600' }}">Vehicle Logs</a>
                        <a href="{{ $adminDailyReportsUrl }}" class="flex h-16 items-center px-3 text-sm font-semibold {{ request()->routeIs('admin.reports.*') || request()->is('admin/reports*') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-blue-600' }}">Daily Reports</a>
                    </div>
                @elseif(Auth::check() && Auth::user()->role === 'guard')
                    <div class="hidden h-16 items-center gap-1 md:flex">
                        <a href="{{ route('guard.dashboard') }}" class="flex h-16 items-center px-3 text-sm font-semibold {{ request()->routeIs('guard.dashboard') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-blue-600' }}">Gate Portal</a>
                        <a href="{{ route('guard.logs') }}" class="flex h-16 items-center px-3 text-sm font-semibold {{ request()->routeIs('guard.logs') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-blue-600' }}">Gate Logs</a>
                    </div>
                @endif
            </div>

            @auth
                <div class="hidden md:block">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger"><button class="flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700 shadow-sm"><span class="grid h-7 w-7 place-items-center rounded-lg bg-blue-50 text-xs font-extrabold text-blue-700">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span><span>{{ Auth::user()->name }}</span><svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg></button></x-slot>
                        <x-slot name="content"><x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link><form method="POST" action="{{ route('logout') }}">@csrf<x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link></form></x-slot>
                    </x-dropdown>
                </div>
                <button @click="open=!open" class="rounded-lg p-2 text-slate-500 md:hidden"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg></button>
            @endauth
        </div>
    </div>

    <div x-show="open" x-cloak class="border-t border-slate-100 bg-white px-4 py-3 md:hidden">
        @if(Auth::check() && Auth::user()->role === 'admin')
            <div class="space-y-1">
                <a class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700" href="{{ route('users.index') }}">User Management</a>
                <a class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700" href="{{ route('admin.zones.index') }}">Parking Zones</a>
                <a class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700" href="{{ route('admin.vehicle-logs.index') }}">Vehicle Logs</a>
                <a class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700" href="{{ $adminDailyReportsUrl }}">Daily Reports</a>
            </div>
        @elseif(Auth::check() && Auth::user()->role === 'guard')
            <div class="space-y-1"><a class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700" href="{{ route('guard.dashboard') }}">Gate Portal</a><a class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700" href="{{ route('guard.logs') }}">Gate Logs</a></div>
        @endif
    </div>
</nav>
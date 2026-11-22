<x-app-layout>
    <style>
        .users-index-shell,
        .users-index-shell * {
            box-sizing: border-box;
        }

        .users-index-shell {
            position: relative;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-variant-numeric: tabular-nums;
            color: #0f172a;
            background: #0f172a;
            overflow-x: hidden;
        }

        .users-scene-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .users-scene-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.34) 0%, rgba(15, 23, 42, 0.48) 100%);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        .users-surface-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .users-count-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
            font-size: 12px;
            font-weight: 800;
        }

        .users-data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .users-data-table thead th {
            padding: 13px 20px;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #475569;
            background: #f8fafc;
            border-bottom: 1.5px solid #e2e8f0;
            white-space: nowrap;
        }

        .users-data-table tbody td {
            padding: 15px 20px;
            font-size: 13.5px;
            font-weight: 600;
            color: #0f172a;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .users-data-table tbody tr:hover {
            background: #f8fafc;
        }
    </style>

    @php
        $usersCollection = collect($users ?? []);
        $totalUsersCount = $usersCollection->count();
        $adminUsersCount = $usersCollection->where('role', 'admin')->count();
        $guardUsersCount = $usersCollection->where('role', 'guard')->count();
        $driverUsersCount = $usersCollection->where('role', 'driver')->count();
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-700">System Access</p>
                <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-950">User Management</h2>
            </div>
            <a
                href="{{ route('users.create') }}"
                class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 text-sm font-extrabold text-white shadow-xs transition hover:-translate-y-0.5 hover:bg-blue-800"
            >
                + Add New User
            </a>
        </div>
    </x-slot>

    <div
        x-data="{
            search: '',
            roleFilter: 'all',
            matchesUser(blob, role) {
                const matchRole = this.roleFilter === 'all' || String(role || '').toLowerCase() === this.roleFilter;
                const q = this.search.trim().toLowerCase();
                const matchQuery = !q || blob.toLowerCase().includes(q);
                return matchRole && matchQuery;
            }
        }"
        class="users-index-shell min-h-[calc(100vh-64px)] py-8 sm:py-10"
    >
        <!-- Welcome-Style Campus Scene Background -->
        <div class="users-scene-bg" aria-hidden="true">
            <svg class="h-full w-full object-cover" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="usrBgSky" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#3b82f6" />
                        <stop offset="55%" stop-color="#93c5fd" />
                        <stop offset="100%" stop-color="#e0f2fe" />
                    </linearGradient>
                    <linearGradient id="usrBgAsphalt" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#475569" />
                        <stop offset="45%" stop-color="#334155" />
                        <stop offset="100%" stop-color="#1e293b" />
                    </linearGradient>
                    <linearGradient id="usrBgBldg" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f8fafc" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                </defs>
                <rect width="1600" height="440" fill="url(#usrBgSky)" />
                <ellipse cx="420" cy="115" rx="95" ry="26" fill="#ffffff" fill-opacity="0.72" />
                <ellipse cx="1180" cy="95" rx="115" ry="28" fill="#ffffff" fill-opacity="0.68" />
                <rect x="640" y="150" width="960" height="245" fill="url(#usrBgBldg)" />
                <polygon points="0,425 1600,425 1600,900 0,900" fill="url(#usrBgAsphalt)" />
                <polygon points="410,565 755,565 750,578 405,578" fill="#eab308" />
                <polygon points="1185,635 1600,635 1600,652 1175,652" fill="#eab308" />
            </svg>
            <div class="users-scene-scrim"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-900 shadow-xs">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Summary Strip -->
            <div class="users-surface-card flex flex-wrap items-center justify-between gap-3 px-5 py-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-600 mr-1">Accounts Overview:</span>
                    <span class="users-count-chip">Total: <strong>{{ $totalUsersCount }}</strong></span>
                    <span class="users-count-chip">Admins: <strong>{{ $adminUsersCount }}</strong></span>
                    <span class="users-count-chip">Guards: <strong>{{ $guardUsersCount }}</strong></span>
                    <span class="users-count-chip">Drivers: <strong>{{ $driverUsersCount }}</strong></span>
                </div>

                <a
                    href="{{ route('users.create') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 text-xs font-extrabold text-white shadow-xs transition hover:-translate-y-0.5 hover:bg-blue-800"
                >
                    + Add New User
                </a>
            </div>

            <!-- Main Accounts Table Card -->
            <div class="users-surface-card overflow-hidden">
                <div class="border-b border-slate-200 px-6 py-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-base font-black text-slate-950">System accounts</h3>
                        <p class="mt-0.5 text-xs font-semibold text-slate-500">Admin, guard, and driver accounts.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Search name, email, or gate..."
                            class="h-9 w-56 rounded-xl border-slate-300 text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:border-blue-600 focus:ring-blue-600"
                        >
                        <button
                            type="button"
                            @click="roleFilter = 'all'"
                            :class="roleFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                            class="h-9 px-3 rounded-xl text-xs font-extrabold transition"
                        >All</button>
                        <button
                            type="button"
                            @click="roleFilter = 'admin'"
                            :class="roleFilter === 'admin' ? 'bg-rose-700 text-white' : 'bg-rose-50 text-rose-800 hover:bg-rose-100'"
                            class="h-9 px-3 rounded-xl text-xs font-extrabold transition"
                        >Admin</button>
                        <button
                            type="button"
                            @click="roleFilter = 'guard'"
                            :class="roleFilter === 'guard' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100'"
                            class="h-9 px-3 rounded-xl text-xs font-extrabold transition"
                        >Guard</button>
                        <button
                            type="button"
                            @click="roleFilter = 'driver'"
                            :class="roleFilter === 'driver' ? 'bg-emerald-700 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100'"
                            class="h-9 px-3 rounded-xl text-xs font-extrabold transition"
                        >Driver</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="users-data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                @php
                                    $userBlob = implode(' ', [
                                        $user->name ?? '',
                                        $user->email ?? '',
                                        $user->role ?? '',
                                        $user->assigned_gate ?? '',
                                    ]);
                                @endphp
                                <tr x-show="matchesUser(@js($userBlob), @js($user->role))">
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-slate-900 text-xs font-black text-white">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                            <div>
                                                <div class="font-extrabold text-slate-950">{{ $user->name }}</div>
                                                @if($user->role === 'guard' && !empty($user->assigned_gate))
                                                    <div class="text-[11px] font-bold text-blue-700">{{ $user->assigned_gate }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-slate-600">{{ $user->email }}</td>
                                    <td>
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-extrabold {{ $user->role === 'admin' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($user->role === 'guard' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex justify-end gap-2">
                                            <a
                                                href="{{ route('users.edit', $user) }}"
                                                class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-amber-200 bg-amber-50 px-3 text-xs font-extrabold text-amber-800 transition hover:bg-amber-100"
                                            >
                                                ✎ Edit
                                            </a>
                                            <form
                                                action="{{ route('users.destroy', $user) }}"
                                                method="POST"
                                                onsubmit="return confirm('Delete this user account?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-3 text-xs font-extrabold text-rose-700 transition hover:bg-rose-100"
                                                >
                                                    ⌫ Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-sm font-semibold text-slate-500">
                                        No user accounts found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
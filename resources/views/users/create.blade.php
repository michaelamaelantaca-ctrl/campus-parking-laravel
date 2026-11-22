<x-app-layout>
    <style>
        .create-user-shell,
        .create-user-shell * {
            box-sizing: border-box;
        }

        .create-user-shell {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #0f172a;
        }

        .create-user-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-top: 4px solid #2563eb;
            border-radius: 18px;
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.06);
        }

        .create-user-field {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 11px;
            background-color: #ffffff;
            color: #0f172a;
            font-size: 14px;
            font-weight: 600;
            transition: border-color 0.16s ease, box-shadow 0.16s ease;
        }

        .create-user-field:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.16);
        }

        .create-user-label {
            display: block;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #334155;
            margin-bottom: 6px;
        }
    </style>

    @php
        $selectedRole = old('role', 'guard');
        $selectedGate = old('assigned_gate', 'Gate 1 — Main Campus Gate');
        $gateOptions = [
            'Gate 1 — Main Campus Gate',
            'Gate 2 — North / Back Gate',
            'Gate 3 — ICT / Gymnasium Gate',
        ];
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-black uppercase tracking-[0.16em] text-blue-700">System Access</p>
                <h2 class="mt-0.5 text-2xl font-black tracking-tight text-slate-950 leading-tight">Create User Account</h2>
            </div>
            <a
                href="{{ route('users.index') }}"
                class="inline-flex items-center justify-center rounded-xl bg-slate-700 px-4 py-2.5 text-sm font-bold text-white shadow-xs transition hover:-translate-y-0.5 hover:bg-slate-800"
            >
                &larr; Back to Users
            </a>
        </div>
    </x-slot>

    <div
        x-data="{ role: @js($selectedRole) }"
        class="create-user-shell min-h-[calc(100vh-64px)] bg-slate-100 py-10"
    >
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="create-user-card overflow-hidden p-6 sm:p-7">
                <div class="mb-5 flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <p class="text-sm font-black text-slate-950">New Account Details</p>
                        <p class="text-xs font-semibold text-slate-500">Create an admin, security guard, or driver account.</p>
                    </div>
                    <span class="rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-[11px] font-extrabold uppercase tracking-wider text-blue-700" x-text="role"></span>
                </div>

                <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="create-user-label">Full Name</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="create-user-field"
                            required
                        >
                        @error('name')
                            <p class="mt-1.5 text-xs font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="create-user-label">Email Address</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="create-user-field"
                            required
                        >
                        @error('email')
                            <p class="mt-1.5 text-xs font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="create-user-label">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="new-password"
                            class="create-user-field"
                            required
                        >
                        @error('password')
                            <p class="mt-1.5 text-xs font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label for="role" class="create-user-label">Assign Role</label>
                        <select
                            id="role"
                            name="role"
                            x-model="role"
                            class="create-user-field"
                            required
                        >
                            <option value="driver" {{ $selectedRole === 'driver' ? 'selected' : '' }}>Student / Faculty Driver</option>
                            <option value="guard" {{ $selectedRole === 'guard' ? 'selected' : '' }}>Security Guard</option>
                            <option value="admin" {{ $selectedRole === 'admin' ? 'selected' : '' }}>System Administrator</option>
                        </select>
                        @error('role')
                            <p class="mt-1.5 text-xs font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assigned Gate (Shown when Guard role is selected) -->
                    <div x-show="role === 'guard'" x-cloak class="rounded-2xl border border-blue-200 bg-blue-50/70 p-4 space-y-2">
                        <label for="assigned_gate" class="create-user-label text-blue-950">Assigned Gate</label>
                        <select
                            id="assigned_gate"
                            name="assigned_gate"
                            class="create-user-field"
                        >
                            @foreach ($gateOptions as $gate)
                                <option value="{{ $gate }}" {{ $selectedGate === $gate ? 'selected' : '' }}>
                                    {{ $gate }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] font-semibold text-blue-800">
                            Itinatalaga ang gate na ito sa guard account (`assigned_gate`).
                        </p>
                        @error('assigned_gate')
                            <p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="pt-2 flex justify-end gap-2.5">
                        <a
                            href="{{ route('users.index') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-bold text-slate-800 transition hover:bg-slate-300"
                        >
                            Cancel
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-extrabold text-white shadow-xs transition hover:-translate-y-0.5 hover:bg-blue-800 cursor-pointer"
                        >
                            Save Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
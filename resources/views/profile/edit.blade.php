<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(Auth::check() && Auth::user()->role === 'guard')
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border-l-4 border-blue-600">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Guard Duty & Gate Assignment') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Current gate duty details for your security guard account.') }}
                        </p>

                        <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="rounded-lg bg-slate-50 p-3 border border-slate-200">
                                <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Role</dt>
                                <dd class="mt-1 font-extrabold text-slate-900 uppercase">{{ Auth::user()->role }}</dd>
                            </div>
                            <div class="rounded-lg bg-blue-50 p-3 border border-blue-200">
                                <dt class="text-xs font-bold uppercase tracking-wider text-blue-700">Assigned Gate</dt>
                                <dd class="mt-1 font-extrabold text-blue-950">
                                    {{ Auth::user()->assigned_gate ?? 'Gate 1 — Main Campus Gate' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
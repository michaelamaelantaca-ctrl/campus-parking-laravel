<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-amber-600">
                    {{ __('Security Operations') }}
                </p>
                <h2 class="mt-0.5 text-2xl font-extrabold tracking-tight text-slate-900">
                    {{ __('Gate Log Dashboard - Security Personnel') }}
                </h2>
            </div>
            <span class="inline-flex w-fit shrink-0 items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ __('Gate Active') }}
            </span>
        </div>
    </x-slot>

    <style>
        .guard-welcome-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%);
            border: 1px solid #fde68a;
            border-left: 5px solid #f59e0b;
            border-radius: 1.25rem;
            box-shadow: 0 10px 25px -10px rgba(15, 23, 42, 0.12);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .guard-welcome-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 30px -12px rgba(15, 23, 42, 0.16);
        }
        .guard-badge-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 1rem;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 8px 16px -6px rgba(217, 119, 6, 0.55);
        }
    </style>

    <div class="guard-dashboard-wrap py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="guard-welcome-card p-6 sm:p-7">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                    <div class="guard-badge-icon" aria-hidden="true">
                        <svg class="w-6 h-6 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 4v5c0 5-3.5 8.5-7 9.5-3.5-1-7-4.5-7-9.5V7l7-4z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 12l2 2 3.5-3.5" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900">
                            Welcome, Guard {{ Auth::user()->name }}!
                        </h3>
                        <p class="mt-1 text-sm sm:text-base text-slate-600 leading-relaxed">
                            Log incoming vehicle plate numbers (Check-In) and release occupied slots upon exit (Check-Out).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
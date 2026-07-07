<div
    x-data
    class="pointer-events-none fixed inset-x-0 top-4 z-[100] flex flex-col items-center gap-2 px-4 sm:items-end sm:px-6"
    aria-live="polite"
>
    <template x-for="toast in $store.toast.items" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto w-full max-w-sm rounded-lg border px-4 py-3 text-sm shadow-elevated"
            :class="{
                'border-success-500/30 bg-white text-success-800 dark:bg-slate-800 dark:text-success-400': toast.type === 'success',
                'border-danger-500/30 bg-white text-danger-800 dark:bg-slate-800 dark:text-danger-400': toast.type === 'error',
                'border-primary-500/30 bg-white text-primary-800 dark:bg-slate-800 dark:text-primary-400': toast.type === 'info',
            }"
            x-text="toast.message"
        ></div>
    </template>
</div>

@if(session('success'))
<script>document.addEventListener('alpine:init', () => Alpine.store('toast').show(@js(session('success')), 'success'));</script>
@endif

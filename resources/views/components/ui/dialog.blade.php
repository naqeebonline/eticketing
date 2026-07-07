<div
    x-data
    x-cloak
    x-show="$store.dialog.open"
    x-on:keydown.escape.window="$store.dialog.dismiss()"
    class="fixed inset-0 z-[110] flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    :aria-label="$store.dialog.title"
>
    <div
        x-show="$store.dialog.open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
        x-on:click="$store.dialog.dismiss()"
    ></div>

    <div
        x-show="$store.dialog.open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative w-full max-w-md overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900"
        x-on:click.stop
    >
        <div class="flex items-start gap-4 p-6">
            <div
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl"
                :class="{
                    'bg-primary-100 text-primary-600 dark:bg-primary-500/15 dark:text-primary-400': $store.dialog.variant === 'info',
                    'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400': $store.dialog.variant === 'warning',
                    'bg-red-100 text-red-600 dark:bg-red-500/15 dark:text-red-400': $store.dialog.variant === 'danger',
                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400': $store.dialog.variant === 'success',
                }"
            >
                <template x-if="$store.dialog.variant === 'danger'">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                </template>
                <template x-if="$store.dialog.variant === 'warning'">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </template>
                <template x-if="$store.dialog.variant === 'success'">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </template>
                <template x-if="$store.dialog.variant === 'info'">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </template>
            </div>

            <div class="min-w-0 flex-1">
                <h2 class="font-display text-lg font-semibold text-slate-900 dark:text-white" x-text="$store.dialog.title"></h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300" x-text="$store.dialog.message"></p>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-slate-50/80 px-6 py-4 sm:flex-row sm:justify-end dark:border-slate-800 dark:bg-slate-900/80">
            <button
                type="button"
                x-show="$store.dialog.type === 'confirm'"
                x-on:click="$store.dialog.dismiss()"
                class="btn-secondary w-full sm:w-auto"
                x-text="$store.dialog.cancelLabel"
            ></button>
            <button
                type="button"
                x-on:click="$store.dialog.accept()"
                class="w-full sm:w-auto"
                :class="$store.dialog.variant === 'danger' ? 'btn-danger' : 'btn-primary'"
                x-text="$store.dialog.confirmLabel"
            ></button>
        </div>
    </div>
</div>

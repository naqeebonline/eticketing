@props([
    'showStandUsersLink' => false,
])

<x-ui.form-section
    title="Assign users (optional)"
    description="Ek stand par multiple users — optional, baad mein Stand Users se bhi assign kar sakte hain."
    :accent="true"
    icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1"
>
    @if($showStandUsersLink)
    <p class="mb-3 text-sm text-slate-600 dark:text-slate-400">
        <a href="{{ route('admin.terminal-users.index') }}" class="font-semibold text-primary-600 hover:underline">Stand Users</a>
        — alag page se bhi manage kar sakte hain.
    </p>
    @endif

    <template x-if="terminalUsers.length === 0">
        <p class="text-sm text-slate-500">
            Is terminal par abhi koi stand user nahi.
            <a href="{{ route('admin.terminal-users.create') }}" class="font-semibold text-primary-600">User add karein</a>
        </p>
    </template>

    <div class="space-y-2" x-show="terminalUsers.length > 0">
        <template x-for="user in terminalUsers" :key="user.id">
            <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 cursor-pointer transition hover:border-primary-200 dark:border-slate-700 dark:hover:border-primary-800">
                <input
                    type="checkbox"
                    name="user_ids[]"
                    :value="user.id"
                    x-model.number="selectedUserIds"
                    class="rounded border-slate-300 text-primary-600"
                >
                <span class="min-w-0 flex-1 text-sm">
                    <span class="font-medium text-slate-800 dark:text-slate-200" x-text="user.name"></span>
                    <span class="text-slate-400 text-xs block" x-text="user.email"></span>
                </span>
            </label>
        </template>
    </div>
</x-ui.form-section>

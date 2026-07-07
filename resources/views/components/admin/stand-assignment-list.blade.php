@props([
    'stands',
    'assignedIds' => [],
])

@if($stands->isEmpty())
<p class="text-sm text-slate-500">Is terminal par abhi koi bus stand nahi. Pehle <a href="{{ route('admin.bus-stands.create') }}" class="font-semibold text-primary-600">bus stand add</a> karein.</p>
@else
<div class="space-y-2">
    @foreach($stands as $stand)
    @php
        $checked = in_array($stand->id, old('stand_ids', $assignedIds));
    @endphp
    <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 transition dark:border-slate-700">
        <input
            type="checkbox"
            name="stand_ids[]"
            value="{{ $stand->id }}"
            @checked($checked)
            class="rounded border-slate-300 text-primary-600"
        >
        <span class="min-w-0 flex-1 text-sm">
            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $stand->displayTitle() }}</span>
            <span class="text-slate-400 text-xs">({{ $stand->address }})</span>
            @if($stand->assignedUsers->isNotEmpty())
            <span class="ml-1 text-xs text-slate-400">
                · also assigned to {{ $stand->assignedUsers->pluck('name')->join(', ') }}
            </span>
            @endif
        </span>
    </label>
    @endforeach
</div>
@endif

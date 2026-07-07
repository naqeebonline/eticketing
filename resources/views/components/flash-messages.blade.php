@if(session('success') || session('error') || $errors->any())
<div class="mb-6 space-y-3 animate-fade-in">
    @if(session('success'))
    <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
    @endif
    @if(session('error'))
    <x-ui.alert variant="error">{{ session('error') }}</x-ui.alert>
    @endif
    @if($errors->any())
    <x-ui.alert variant="error">
        <ul class="list-inside list-disc space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </x-ui.alert>
    @endif
</div>
@endif

@if (session('success'))
    <div class="status-banner" role="status">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="status-banner status-banner--error" role="alert">
        {{ $errors->first() }}
    </div>
@endif

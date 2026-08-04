<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-solid btn-danger']) }}>
    {{ $slot }}
</button>

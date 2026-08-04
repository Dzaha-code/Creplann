@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-coral focus:ring-coral rounded-xl shadow-sm']) }}>

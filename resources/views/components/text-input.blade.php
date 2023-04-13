@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-orange-500 focus:border-orange-600 focus:ring-orange-400 rounded-md shadow-sm']) !!}>

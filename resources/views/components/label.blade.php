<label {{ $attributes->merge(['class' => 'form-label']) }} for="{{ $attributes->get('for') }}">
    {{ $slot }}
</label>

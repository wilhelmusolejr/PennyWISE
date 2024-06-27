<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    <label for="{{ $attributes->get('id') }}" class="form-label">{{ $attributes->get('label') }}</label>
    <input type="{{ $attributes->get('type') }}"
           class="form-control"
           id="{{ $attributes->get('id') }}"
           name="{{ $attributes->get('name') }}"
           value="{{ old($attributes->get('name')) ? old($attributes->get('name')) : $attributes->get('value') }}"
           @if($attributes->get('required') === 'true')  @endif>
</div>

@props([
    'custom_id' => null,
    'form' => null
])

<div class="mt-4 mb-4" @if(!empty($customId)) id="{{ $customId }}" @endif>
    <livewire:dynamic-form :form-id="$form"/>
</div>

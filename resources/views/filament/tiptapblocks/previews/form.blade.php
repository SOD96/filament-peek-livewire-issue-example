@props([
    'custom_id' => null,
    'form' => null
])
@php $formModel = \App\Models\Form::where('id', $form)->first(); @endphp

<div class="mt-4 mb-4" @if(!empty($customId)) id="{{ $customId }}" @endif>
    <p>Form: {{ $formModel->title }}</p>
</div>

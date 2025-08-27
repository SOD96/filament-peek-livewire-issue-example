<div class="py-3 px-3 {{ isset($formModel['styles']['enable_full_width']) && $formModel['styles']['enable_full_width'] ? 'w-full' : 'max-w-5xl mx-auto' }}"
     style="@if(!empty($formModel['styles']['background_color'])) background-color:{{$formModel['styles']['background_color']}};@endif">
    <div class="mx-auto">
        @if($this->showSuccessMessage === true && $formModel['settings']['success_message'])
            <div class="prose prose-lg prose-primary max-w-none py-5">
                {!! tiptap_converter()->asHTML($formModel['settings']['success_message']) !!}
            </div>
        @else
            <form wire:submit="create" class="py-5">

                @if($formModel['heading'])
                    <h2 class="@if(!empty($formModel['styles']['enable_centering']) && $formModel['styles']['enable_centering'] === true) text-center @endif text-2xl font-bold text-gray-900">{{ $formModel['heading'] }}</h2>
                @endif

                @if($formModel['intro'])
                    <div class="@if(!empty($formModel['styles']['enable_centering']) && $formModel['styles']['enable_centering'] === true) text-center @endif prose-lg mt-4">
                        {!! tiptap_converter()->asHTML($formModel['intro']) !!}
                    </div>
                @endif

                    @if(isset($formModel['styles']['enable_centering']) && $formModel['styles']['enable_centering'] === true)
                        <div class="flex flex-col items-center justify-center px-6 mt-4">
                            <div class="gap-1 w-full max-w-lg">
                                {{ $this->form }}
                                <div class="flex w-full items-center justify-center mt-5">
                                    <button type="submit" class="mt-1 rounded-md bg-brand-600 px-8 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-brand-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        {{ $formModel['settings']['cta_text'] }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mt-5 ">
                            {{ $this->form }}
                            <button type="submit" class="mt-1 rounded-md bg-brand-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-brand-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                {{ $formModel['settings']['cta_text'] }}
                            </button>
                        </div>
                    @endif

            </form>

            <x-filament-actions::modals />
        @endif
    </div>

</div>

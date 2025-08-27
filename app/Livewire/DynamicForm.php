<?php

namespace App\Livewire;

use App\Models\Form as FormModel;
use App\Models\FormSubmission;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class DynamicForm extends Component implements HasForms
{
    use InteractsWithForms;

    public FormModel $formModel;

    /** @var array<string, mixed> */
    public array $data = [];

    public bool $showSuccessMessage = false;

    public function mount(int $formId): void
    {
        $this->formModel = FormModel::findOrFail($formId);
        /** @phpstan-ignore-next-line */
        $this->form->fill([]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getFormSchema(): array
    {
        $hideLabels = $this->formModel->styles['hide_labels'] ?? false;
        $inlineLabels = $this->formModel->styles['enable_inline_labels'] ?? false;
        $fields = collect($this->formModel->fields)->map(function ($field) use ($inlineLabels, $hideLabels) {
            $fieldKey = Str::snake(Str::lower($field['label']));
            $component = match ($field['type']) {
                'text' => TextInput::make($fieldKey)
                    ->label(($hideLabels) ? '' : $field['label'])
                    ->placeholder($field['placeholder']),
                'email' => TextInput::make($fieldKey)
                    ->label(($hideLabels) ? '' : $field['label'])
                    ->email()
                    ->placeholder($field['placeholder']),
                'phone' => PhoneInput::make($fieldKey)
                    ->label(($hideLabels) ? '' : $field['label'])
                    ->placeholder($field['placeholder'])
                    ->initialCountry('IN')
                    ->inputNumberFormat(PhoneInputNumberType::INTERNATIONAL)
                    ->displayNumberFormat(PhoneInputNumberType::E164)
                    ->focusNumberFormat(PhoneInputNumberType::E164),
                'textarea' => Textarea::make($fieldKey)
                    ->label(($hideLabels) ? '' : $field['label'])
                    ->placeholder($field['placeholder']),
                'select' => Select::make($fieldKey)
                    ->label(($hideLabels) ? '' : $field['label'])
                    ->options(array_combine(explode(',', $field['options']), explode(',', $field['options']))),
                default => null,
            };

            if (! $component) {
                return null;
            }

            if (! empty($field['helper_text'])) {
                $component->helperText($field['helper_text']);
            }

            if (! empty($field['hint_text'])) {
                $component->hint($field['hint_text']);
            }

            if (! empty($field['validation'])) {
                $component->rules($field['validation']);
            }

            if ($inlineLabels) {
                $component->inlineLabel();
            }

            return $component;
        })->filter()->toArray();

        $labelCheckbox = new HtmlString('I agree to the <a href="'.($this->formModel->settings['privacy_policy_url'] ?? '/privacy-policy').'" target="_blank" class="underline text-primary-700">privacy policy</a>');

        if ($this->formModel->settings['enable_privacy'] ?? false) {
            $fields[] = Checkbox::make('privacy_agreement')
                ->label($labelCheckbox)
                // Vertically allign the label
                ->extraAttributes([
                    'class' => 'flex items-center',
                ])
                ->required();
        }

        return $fields;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data');
    }

    /**
     * @throws ValidationException
     */
    /** @phpstan-ignore-next-line */
    public function create()
    {
        /* @phpstan-ignore-next-line */
        $formData = array_merge($this->form->getState(), ['tags' => $this->formModel['settings']['tags'] ?? []]);
        $formData = array_merge($formData, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'heading' => $this->formModel['heading'],
        ]);
        $this->formModel->submissions()->create([
            'data' => $formData,
        ]);

        if ($this->formModel['settings']['enable_success_url']) {
            return redirect($this->formModel['settings']['success_url']);
        }

        $this->showSuccessMessage = true;
    }

    public function render(): View|Factory|Application
    {
        /** @var view-string $viewName */
        $viewName = 'livewire.dynamic-form';

        return view($viewName);
    }
}

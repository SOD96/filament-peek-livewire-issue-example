<?php

namespace App\Models;

use Database\Factories\FormFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    /** @use HasFactory<FormFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'heading',
        'intro',
        'fields',
        'settings',
        'styles',
    ];

    protected $casts = [
        'intro' => 'array',
        'fields' => 'array',
        'settings' => 'array',
        'styles' => 'array',
    ];

    /**
     * @return HasMany<FormSubmission, $this>
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }
}

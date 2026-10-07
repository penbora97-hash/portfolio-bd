<?php

namespace App\Http\Controllers\Api;

use App\Models\Experience;

class ExperienceController extends CrudController
{
    protected string $model = Experience::class;

    protected function rules(): array
    {
        return [
            'company'     => ['required', 'string', 'max:255'],
            'position'    => ['required', 'string', 'max:255'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
        ];
    }
}

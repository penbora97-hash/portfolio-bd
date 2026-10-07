<?php

namespace App\Http\Controllers\Api;

use App\Models\Education;

class EducationController extends CrudController
{
    protected string $model = Education::class;

    protected function rules(): array
    {
        return [
            'school'     => ['required', 'string', 'max:255'],
            'degree'     => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Skill;

class SkillController extends CrudController
{
    protected string $model = Skill::class;
    protected bool $ownedByUser = false;

    protected function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'level'    => ['nullable', 'integer', 'min:0', 'max:100'],
            'icon'     => ['nullable', 'string', 'max:255'],
        ];
    }
}

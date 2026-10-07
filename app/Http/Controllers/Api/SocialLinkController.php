<?php

namespace App\Http\Controllers\Api;

use App\Models\SocialLink;

class SocialLinkController extends CrudController
{
    protected string $model = SocialLink::class;

    protected function rules(): array
    {
        return [
            'platform' => ['required', 'string', 'max:100'],
            'url'      => ['required', 'url'],
        ];
    }
}

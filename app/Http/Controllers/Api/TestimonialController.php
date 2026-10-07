<?php

namespace App\Http\Controllers\Api;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends CrudController
{
    protected string $model = Testimonial::class;

    protected function rules(): array
    {
        return [
            'author_name' => ['required', 'string', 'max:255'],
            'author_role' => ['nullable', 'string', 'max:255'],
            'content'     => ['required', 'string'],
            'is_visible'  => ['boolean'],
        ];
    }

    // Visitors only see visible ones; the logged-in admin sees all
    protected function query(Request $request)
    {
        $query = Testimonial::query()->latest();

        if (! $request->user('sanctum')) {
            $query->where('is_visible', true);
        }

        return $query;
    }
}

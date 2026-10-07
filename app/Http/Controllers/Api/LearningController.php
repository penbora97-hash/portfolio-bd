<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Learning;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    public function index()
    {
        return Learning::orderBy('sort_order')->get();
    }

    public function show(Learning $learning)
    {
        return $learning;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'icon'       => 'nullable|string|max:100',
            'progress'   => 'integer|min:0|max:100',
            'sort_order' => 'integer',
        ]);

        $data['user_id'] = $request->user()->id;

        $learning = Learning::create($data);

        return response()->json($learning, 201);
    }

    public function update(Request $request, Learning $learning)
    {
        $data = $request->validate([
            'name'       => 'sometimes|string|max:255',
            'icon'       => 'nullable|string|max:100',
            'progress'   => 'integer|min:0|max:100',
            'sort_order' => 'integer',
        ]);

        $learning->update($data);

        return response()->json($learning);
    }

    public function destroy(Learning $learning)
    {
        $learning->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectImageController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'image'   => ['required', 'image', 'max:4096'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $image = $project->images()->create([
            'image_path' => $request->file('image')->store('project-images', 'public'),
            'caption'    => $data['caption'] ?? null,
        ]);

        return response()->json($image, 201);
    }

    public function destroy(ProjectImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['message' => 'Deleted']);
    }
}

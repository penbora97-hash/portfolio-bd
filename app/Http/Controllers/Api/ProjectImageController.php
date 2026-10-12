<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;

class ProjectImageController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'image'   => ['required', 'image', 'max:4096'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        // ✅ Upload ទៅ Cloudinary
        $uploadedFileUrl = $request->file('image')
            ->storeOnCloudinary('portfolio/project-images')
            ->getSecurePath();

        $image = $project->images()->create([
            'image_path' => $uploadedFileUrl,
            'caption'    => $data['caption'] ?? null,
        ]);

        return response()->json($image, 201);
    }

    public function destroy(ProjectImage $image)
    {
        $image->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        return response()->json(
            Project::with('skills')->orderBy('sort_order')->latest()->get()
        );
    }

    public function show(Project $project)
    {
        return response()->json($project->load(['skills', 'images']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'thumbnail'   => ['nullable', 'image', 'max:2048'],
            'demo_url'    => ['nullable', 'url'],
            'github_url'  => ['nullable', 'url'],
            'is_featured' => ['boolean'],
            'sort_order'  => ['integer'],
            'skill_ids'   => ['array'],
            'skill_ids.*' => ['exists:skills,id'],
        ]);

        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->uniqueSlug($data['title']);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }

        $project = Project::create($data);
        $project->skills()->sync($data['skill_ids'] ?? []);

        return response()->json($project->load('skills'), 201);
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title'       => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'thumbnail'   => ['nullable', 'image', 'max:2048'],
            'demo_url'    => ['nullable', 'url'],
            'github_url'  => ['nullable', 'url'],
            'is_featured' => ['boolean'],
            'sort_order'  => ['integer'],
            'skill_ids'   => ['array'],
            'skill_ids.*' => ['exists:skills,id'],
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }

        $project->update($data);

        if ($request->has('skill_ids')) {
            $project->skills()->sync($request->input('skill_ids', []));
        }

        return response()->json($project->load('skills'));
    }

    public function destroy(Project $project)
    {
        try {
            // លុបរូបភាព Thumbnail
            if ($project->thumbnail) {
                \Storage::disk('public')->delete($project->thumbnail);
            }

            // លុបរូបភាព Gallery ទាំងអស់ (បើមាន)
            foreach ($project->images as $image) {
                \Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            // លុបទំនាក់ទំនង Skill (pivot table)
            $project->skills()->detach();

            // លុប Project
            $project->delete();

            return response()->json(['message' => 'Project deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete project',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}

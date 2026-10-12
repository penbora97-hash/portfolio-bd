<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::with('skills')->orderBy('sort_order')->latest()->get();
    }

    public function show(Project $project)
    {
        return $project->load(['skills', 'images']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'thumbnail'   => ['nullable', 'image', 'max:4096'],
            'demo_url'    => ['nullable', 'url'],
            'github_url'  => ['nullable', 'url'],
            'is_featured' => ['boolean'],
            'sort_order'  => ['integer'],
            'skill_ids'   => ['array'],
            'skill_ids.*' => ['exists:skills,id'],
        ]);

        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->uniqueSlug($data['title']);

        // ✅ Upload ទៅ Cloudinary
        if ($request->hasFile('thumbnail')) {
            $uploadedFileUrl = $request->file('thumbnail')
                ->storeOnCloudinary('portfolio/projects')
                ->getSecurePath();
            $data['thumbnail'] = $uploadedFileUrl;
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
            'thumbnail'   => ['nullable', 'image', 'max:4096'],
            'demo_url'    => ['nullable', 'url'],
            'github_url'  => ['nullable', 'url'],
            'is_featured' => ['boolean'],
            'sort_order'  => ['integer'],
            'skill_ids'   => ['array'],
            'skill_ids.*' => ['exists:skills,id'],
        ]);

        // ✅ Upload ទៅ Cloudinary (បើមានរូបថ្មី)
        if ($request->hasFile('thumbnail')) {
            $uploadedFileUrl = $request->file('thumbnail')
                ->storeOnCloudinary('portfolio/projects')
                ->getSecurePath();
            $data['thumbnail'] = $uploadedFileUrl;
        }

        $project->update($data);

        if ($request->has('skill_ids')) {
            $project->skills()->sync($request->input('skill_ids', []));
        }

        return response()->json($project->load('skills'));
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'Deleted']);
    }

    private function uniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $i = 2;
        while (Project::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}

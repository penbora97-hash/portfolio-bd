<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    // Public: Get all certificates
    public function index()
    {
        return Certificate::orderBy('sort_order')->latest()->get();
    }

    public function show(Certificate $certificate)
    {
        return $certificate;
    }

    // Admin: Create
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'issuer'      => 'required|string|max:255',
            'date'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'url'         => 'nullable|url',
            'sort_order'  => 'integer',
            'image'       => 'nullable|image|max:4096',
        ]);

        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('certificates', 'public');
        }

        $certificate = Certificate::create($data);

        return response()->json($certificate, 201);
    }

    // Admin: Update
    public function update(Request $request, Certificate $certificate)
    {
        $data = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'issuer'      => 'sometimes|string|max:255',
            'date'        => 'sometimes|string|max:100',
            'description' => 'nullable|string',
            'url'         => 'nullable|url',
            'sort_order'  => 'integer',
            'image'       => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('image')) {
            // លុបរូបចាស់
            if ($certificate->image_path) {
                Storage::disk('public')->delete($certificate->image_path);
            }
            $data['image_path'] = $request->file('image')->store('certificates', 'public');
        }

        $certificate->update($data);

        return response()->json($certificate);
    }

    // Admin: Delete
    public function destroy(Certificate $certificate)
    {
        if ($certificate->image_path) {
            Storage::disk('public')->delete($certificate->image_path);
        }
        $certificate->delete();

        return response()->json(['message' => 'Deleted']);
    }
}

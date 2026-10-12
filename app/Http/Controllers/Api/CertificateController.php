<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        return Certificate::orderBy('sort_order')->latest()->get();
    }

    public function show(Certificate $certificate)
    {
        return $certificate;
    }

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

        // ✅ Upload ទៅ Cloudinary
        if ($request->hasFile('image')) {
            $uploadedFileUrl = $request->file('image')
                ->storeOnCloudinary('portfolio/certificates')
                ->getSecurePath();
            $data['image_path'] = $uploadedFileUrl;
        }

        $certificate = Certificate::create($data);

        return response()->json($certificate, 201);
    }

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

        // ✅ Upload ទៅ Cloudinary (បើមានរូបថ្មី)
        if ($request->hasFile('image')) {
            $uploadedFileUrl = $request->file('image')
                ->storeOnCloudinary('portfolio/certificates')
                ->getSecurePath();
            $data['image_path'] = $uploadedFileUrl;
        }

        $certificate->update($data);

        return response()->json($certificate);
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

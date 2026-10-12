<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = User::with(['profile', 'socialLinks'])->firstOrFail();

        return response()->json([
            'id'             => $user->id,
            'name'           => $user->name,
            'email'          => $user->email,
            'role'           => $user->profile?->headline ?? 'Full-Stack Developer',
            'headline'       => $user->profile?->headline,
            'bio'            => $user->profile?->bio,
            'location'       => $user->profile?->location,
            'phone'          => $user->profile?->phone,
            'photo_url'      => $user->profile?->avatar,
            'resume_url'     => $user->profile?->resume_path,
            'years_learning' => $user->profile?->years_learning ?? 2,
            'social_links'   => $user->socialLinks ?? [],
        ]);
    }

    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'name'           => ['sometimes', 'string', 'max:255'],
                'headline'       => ['nullable', 'string', 'max:255'],
                'bio'            => ['nullable', 'string'],
                'phone'          => ['nullable', 'string', 'max:50'],
                'location'       => ['nullable', 'string', 'max:255'],
                'years_learning' => ['nullable', 'integer', 'min:0'],
                'avatar'         => ['nullable', 'image', 'max:4096'],
                'resume'         => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            ]);

            $user = $request->user();
            if (isset($data['name'])) {
                $user->update(['name' => $data['name']]);
            }

            $profile = Profile::firstOrNew(['user_id' => $user->id]);
            $profile->fill(
                collect($data)->only(['headline', 'bio', 'phone', 'location', 'years_learning'])->all()
            );

            // ✅ Upload Avatar ទៅ Cloudinary
            if ($request->hasFile('avatar')) {
                $uploaded = $request->file('avatar')->storeOnCloudinary('portfolio/avatars');
                $profile->avatar = $uploaded->getSecurePath();
            }

            // ✅ Upload Resume ទៅ Cloudinary
            if ($request->hasFile('resume')) {
                $uploaded = $request->file('resume')->storeOnCloudinary('portfolio/resumes');
                $profile->resume_path = $uploaded->getSecurePath();
            }

            $profile->save();

            return response()->json([
                'message' => 'Profile updated successfully',
                'profile' => $profile,
            ]);
        } catch (\Exception $e) {
            // ✅ បង្ហាញ Error ពិតប្រាកដ
            return response()->json([
                'message' => 'Failed to update profile',
                'error'   => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ], 500);
        }
    }
}

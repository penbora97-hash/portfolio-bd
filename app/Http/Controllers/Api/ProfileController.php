<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // Public: portfolio owner's info
    public function show()
    {
        $user = User::with(['profile', 'socialLinks'])->firstOrFail();

        return response()->json([
            'id'             => $user->id,
            'name'           => $user->name,
            'email'          => $user->email,
            'role'           => $user->profile?->headline ?? 'Full-Stack Developer',
            'headline'       => $user->profile?->headline,
            'bio'            => $user->profile?->bio ?? 'Passionate developer...',
            'location'       => $user->profile?->location ?? 'Phnom Penh, Cambodia',
            'phone'              => $user->profile?->phone,
            'photo_url'      => $user->profile?->avatar
                ? url('storage/' . $user->profile->avatar)
                : null,
            'resume_url'     => $user->profile?->resume_path
                ? url('storage/' . $user->profile->resume_path)
                : null,
            'years_learning' => $user->profile?->years_learning ?? 2, // ✅ ទាញពី DB
            'social_links'   => $user->socialLinks ?? [],
        ]);
    }

    // Admin: create or update profile
    public function update(Request $request)
    {
        $data = $request->validate([
            'name'     => ['sometimes', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'bio'      => ['nullable', 'string'],
            'phone'    => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'avatar'   => ['nullable', 'image', 'max:2048'],
            'resume'   => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $user = $request->user();
        if (isset($data['name'])) {
            $user->update(['name' => $data['name']]);
        }

        $profile = Profile::firstOrNew(['user_id' => $user->id]);
        $profile->fill(collect($data)->only(['headline', 'bio', 'phone', 'location'])->all());

        if ($request->hasFile('avatar')) {
            // លុប Avatar ចាស់
            if ($profile->avatar) {
                \Storage::disk('public')->delete($profile->avatar);
            }
            $profile->avatar = $request->file('avatar')->store('avatars', 'public');
        }
        if ($request->hasFile('resume')) {
            if ($profile->resume_path) {
                \Storage::disk('public')->delete($profile->resume_path);
            }
            $profile->resume_path = $request->file('resume')->store('resumes', 'public');
        }

        $profile->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profile,
        ]);
    }
}

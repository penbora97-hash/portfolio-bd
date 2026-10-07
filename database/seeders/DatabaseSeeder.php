<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Education;
use App\Models\SocialLink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== ADMIN CREDENTIALS ពី ENV ====================
        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        $adminPassword = env('ADMIN_PASSWORD');

        // ✅ ពិនិត្យថា Password ត្រូវបានកំណត់
        if (empty($adminPassword)) {
            throw new \Exception('❌ ADMIN_PASSWORD មិនត្រូវបានកំណត់ក្នុង .env ទេ!');
        }

        if (strlen($adminPassword) < 12) {
            throw new \Exception('❌ ADMIN_PASSWORD ត្រូវមានយ៉ាងតិច 12 តួអក្សរ!');
        }

        // ==================== 1. ADMIN USER ====================
        $user = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name'     => 'Pen Bora',
                'password' => Hash::make($adminPassword),
            ]
        );

        // ==================== 2. PROFILE ====================
        Profile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'headline'       => 'Full-Stack Developer',
                'bio'            => 'I am a passionate Software Engineering student and aspiring Full-Stack Developer based in Phnom Penh, Cambodia.',
                'avatar'         => null,
                'resume_path'    => null,
                'phone'          => null,
                'location'       => 'Phnom Penh, Cambodia',
                'years_learning' => 2,
            ]
        );

        // ==================== 3. SKILLS ====================
        $skills = [
            ['name' => 'HTML',        'level' => 98, 'category' => 'Frontend', 'icon' => 'html5'],
            ['name' => 'React',       'level' => 96, 'category' => 'Frontend', 'icon' => 'react'],
            ['name' => 'Tailwind',    'level' => 96, 'category' => 'Frontend', 'icon' => 'tailwind'],
            ['name' => 'JavaScript',  'level' => 95, 'category' => 'Frontend', 'icon' => 'javascript'],
            ['name' => 'CSS3',        'level' => 94, 'category' => 'Frontend', 'icon' => 'css3'],
            ['name' => 'RESTful API', 'level' => 95, 'category' => 'Backend',  'icon' => 'api'],
            ['name' => 'Laravel',     'level' => 94, 'category' => 'Backend',  'icon' => 'laravel'],
            ['name' => 'MySQL',       'level' => 90, 'category' => 'Backend',  'icon' => 'mysql'],
            ['name' => 'Git',         'level' => 92, 'category' => 'Tools & DevOps', 'icon' => 'git'],
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(['name' => $skill['name']], $skill);
        }

        // ==================== 4. PROJECTS ====================
        $projects = [
            [
                'user_id'     => $user->id,
                'title'       => 'iMusic',
                'slug'        => 'imusic',
                'description' => 'A full-stack music streaming platform where users can discover, search, and stream songs.',
                'thumbnail'   => null,
                'demo_url'    => 'https://imusic-demo.vercel.app',
                'github_url'  => 'https://github.com/penbora97-hash/imusic',
                'is_featured' => true,
                'sort_order'  => 1,
            ],
            [
                'user_id'     => $user->id,
                'title'       => 'TosEat',
                'slug'        => 'toseat',
                'description' => 'A full-stack food ordering and delivery platform.',
                'thumbnail'   => null,
                'demo_url'    => 'https://toseat-demo.vercel.app',
                'github_url'  => 'https://github.com/penbora97-hash/toseat',
                'is_featured' => true,
                'sort_order'  => 2,
            ],
            [
                'user_id'     => $user->id,
                'title'       => 'Play Music',
                'slug'        => 'play-music',
                'description' => 'A music streaming web application built with React and Tailwind CSS.',
                'thumbnail'   => null,
                'demo_url'    => 'https://play-music-demo.vercel.app',
                'github_url'  => 'https://github.com/penbora97-hash/play-music',
                'is_featured' => false,
                'sort_order'  => 3,
            ],
            [
                'user_id'     => $user->id,
                'title'       => 'CineVault',
                'slug'        => 'cinevault',
                'description' => 'A responsive movie discovery website with search and filters.',
                'thumbnail'   => null,
                'demo_url'    => 'https://cinevault-demo.vercel.app',
                'github_url'  => 'https://github.com/penbora97-hash/cinevault',
                'is_featured' => false,
                'sort_order'  => 4,
            ],
        ];

        foreach ($projects as $project) {
            Project::firstOrCreate(['slug' => $project['slug']], $project);
        }

        // ==================== 5. EXPERIENCES ====================
        $experiences = [
            [
                'user_id'     => $user->id,
                'company'     => 'Self-Employed',
                'position'    => 'Freelance Full-Stack Developer',
                'start_date'  => '2024-01-01',
                'end_date'    => null,
                'description' => 'Building custom web applications for clients using React, Laravel, and MySQL.',
            ],
            [
                'user_id'     => $user->id,
                'company'     => 'Tech Startup',
                'position'    => 'Web Development Intern',
                'start_date'  => '2023-06-01',
                'end_date'    => '2023-12-31',
                'description' => 'Contributed to frontend development using React and Tailwind CSS.',
            ],
        ];

        foreach ($experiences as $exp) {
            Experience::firstOrCreate(
                ['company' => $exp['company'], 'position' => $exp['position']],
                $exp
            );
        }

        // ==================== 6. EDUCATION ====================
        $education = [
            [
                'user_id'    => $user->id,
                'school'     => 'Royal University of Phnom Penh',
                'degree'     => 'Bachelor of Science in Software Engineering',
                'start_date' => '2022-10-01',
                'end_date'   => null,
            ],
        ];

        foreach ($education as $edu) {
            Education::firstOrCreate(
                ['school' => $edu['school'], 'degree' => $edu['degree']],
                $edu
            );
        }

        // ==================== 7. SOCIAL LINKS ====================
        $socialLinks = [
            ['user_id' => $user->id, 'platform' => 'github',    'url' => 'https://github.com/penbora97-hash'],
            ['user_id' => $user->id, 'platform' => 'facebook',  'url' => 'https://www.facebook.com/share/1DiHMV5DLf/'],
            ['user_id' => $user->id, 'platform' => 'instagram', 'url' => 'https://www.instagram.com/penbora97/'],
            ['user_id' => $user->id, 'platform' => 'telegram',  'url' => 'https://t.me/pen_bora'],
        ];

        foreach ($socialLinks as $link) {
            SocialLink::firstOrCreate(
                ['user_id' => $link['user_id'], 'platform' => $link['platform']],
                $link
            );
        }

        // ==================== SUCCESS MESSAGE (គ្មាន Password!) ====================
        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📧 Admin Email: ' . $adminEmail);
        $this->command->warn('🔑 Password: [កំណត់ក្នុង .env រួចហើយ]');
    }
}

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
        // ==================== 1. ADMIN USER ====================
        $user = User::create([
            'name'     => 'Pen Bora',
            'email'    => 'penbora@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        // ==================== 2. PROFILE ====================
        Profile::create([
            'user_id'        => $user->id,
            'headline'       => 'Full-Stack Developer',
            'bio'            => 'I am a passionate Software Engineering student and aspiring Full-Stack Developer based in Phnom Penh, Cambodia. I love turning complex problems into simple, beautiful, and intuitive designs.',
            'avatar'         => null,
            'resume_path'    => null,
            'phone'          => null,
            'location'       => 'Phnom Penh, Cambodia',
            'years_learning' => 2,
        ]);

        // ==================== 3. SKILLS ====================
        // Column ដែលមាន: name, category, level, icon
        $skills = [
            // Frontend
            ['name' => 'HTML',        'level' => 98, 'category' => 'Frontend', 'icon' => 'html5'],
            ['name' => 'React',       'level' => 96, 'category' => 'Frontend', 'icon' => 'react'],
            ['name' => 'Tailwind',    'level' => 96, 'category' => 'Frontend', 'icon' => 'tailwind'],
            ['name' => 'JavaScript',  'level' => 95, 'category' => 'Frontend', 'icon' => 'javascript'],
            ['name' => 'CSS3',        'level' => 94, 'category' => 'Frontend', 'icon' => 'css3'],

            // Backend
            ['name' => 'RESTful API', 'level' => 95, 'category' => 'Backend',  'icon' => 'api'],
            ['name' => 'Laravel',     'level' => 94, 'category' => 'Backend',  'icon' => 'laravel'],
            ['name' => 'MySQL',       'level' => 90, 'category' => 'Backend',  'icon' => 'mysql'],

            // Tools
            ['name' => 'Git',         'level' => 92, 'category' => 'Tools & DevOps', 'icon' => 'git'],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        // ==================== 4. PROJECTS ====================
        // Column ដែលមាន: user_id, title, slug, description, thumbnail, demo_url, github_url, is_featured, sort_order
        $projects = [
            [
                'user_id'     => $user->id,
                'title'       => 'iMusic',
                'slug'        => 'imusic',
                'description' => 'A full-stack music streaming platform where users can discover, search, and stream songs. Built with React for the frontend and Laravel REST API for the backend, featuring playlists, artist profiles, and an admin dashboard.',
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
                'description' => 'A full-stack food ordering and delivery platform that connects customers with local restaurants. Built with React for the frontend and Laravel REST API for the backend, featuring a shopping cart, order tracking, and an admin dashboard.',
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
                'description' => 'A music streaming web application built with React, Tailwind CSS, and JavaScript. Users can search songs, create playlists, and play music.',
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
                'description' => 'A responsive movie discovery website with search, genre filters, catalogue sorting, persistent favourites and watchlists, demo authentication, and light and dark themes.',
                'thumbnail'   => null,
                'demo_url'    => 'https://cinevault-demo.vercel.app',
                'github_url'  => 'https://github.com/penbora97-hash/cinevault',
                'is_featured' => false,
                'sort_order'  => 4,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        // ==================== 5. EXPERIENCES ====================
        // Column ដែលមាន: user_id, company, position, start_date, end_date, description
        $experiences = [
            [
                'user_id'     => $user->id,
                'company'     => 'Self-Employed',
                'position'    => 'Freelance Full-Stack Developer',
                'start_date'  => '2024-01-01',
                'end_date'    => null,
                'description' => 'Building custom web applications for clients using React, Laravel, and MySQL. Delivering responsive, scalable, and user-friendly solutions.',
            ],
            [
                'user_id'     => $user->id,
                'company'     => 'Tech Startup',
                'position'    => 'Web Development Intern',
                'start_date'  => '2023-06-01',
                'end_date'    => '2023-12-31',
                'description' => 'Contributed to frontend development using React and Tailwind CSS. Assisted in backend API development with Laravel.',
            ],
        ];

        foreach ($experiences as $exp) {
            Experience::create($exp);
        }

        // ==================== 6. EDUCATION ====================
        // Column ដែលមាន: user_id, school, degree, start_date, end_date
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
            Education::create($edu);
        }

        // ==================== 7. SOCIAL LINKS ====================
        // Column ដែលមាន: user_id, platform, url
        $socialLinks = [
            ['user_id' => $user->id, 'platform' => 'github',    'url' => 'https://github.com/penbora97-hash'],
            ['user_id' => $user->id, 'platform' => 'facebook',  'url' => 'https://www.facebook.com/share/1DiHMV5DLf/'],
            ['user_id' => $user->id, 'platform' => 'instagram', 'url' => 'https://www.instagram.com/penbora97/'],
            ['user_id' => $user->id, 'platform' => 'telegram',  'url' => 'https://t.me/pen_bora'],
        ];

        foreach ($socialLinks as $link) {
            SocialLink::create($link);
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📧 Admin Email: penbora@gmail.com');
        $this->command->info('🔑 Password: passbora88');
    }
}

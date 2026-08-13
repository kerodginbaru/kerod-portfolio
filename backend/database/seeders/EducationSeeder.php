<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            [
                'degree' => 'BSc in Information Technology',
                'institution' => 'Wolkite University',
                'field' => 'Information Technology',
                'start_date' => '2023-09-01', // TODO: replace with your real start date
                'end_date' => 'on-going',   // TODO: replace with your real graduation date
                'description' => 'Core coursework in software development, databases, networking, and systems analysis.',
                'sort_order' => 0,
            ],
            [
                'degree' => 'BA in Management',
                'institution' => 'Wolkite University',
                'field' => 'Management',
                'start_date' => '2021-10-25', // TODO: replace with your real start date
                'end_date' => 'on-going',   // TODO: replace with your real graduation date
                'description' => 'Coursework in business processes, organizational management, and decision-making — the foundation for reading a business problem before designing the system that solves it.',
                'sort_order' => 1,
            ],
        ];

        foreach ($entries as $entry) {
            Education::updateOrCreate(
                ['degree' => $entry['degree'], 'institution' => $entry['institution']],
                $entry
            );
        }
    }
}
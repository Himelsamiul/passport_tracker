<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Agent;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Passport;
use App\Models\PassportCollection;
use App\Models\PassportOfficer;
use App\Models\PassportProcessing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Demo content for a lively dashboard out of the box.
 *
 * Safe to run in production: it only seeds when the core tables are still
 * empty, so an already-in-use database is never touched or duplicated.
 */
class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        // Never overwrite real passports — seed only when the registry is empty.
        // Directory entries (agents/officers/etc.) may already exist; demo ones
        // are simply added alongside them.
        if (Passport::exists()) {
            $this->command?->line('  DemoContentSeeder skipped — passports table already has data.');
            return;
        }

        $this->command?->info('  Seeding demo agents, officers, employees, categories, agencies, passports…');

        // ---------- Directory ----------
        $categories = collect([
            ['name' => 'Work Permit', 'status' => 'active'],
            ['name' => 'Student Visa', 'status' => 'active'],
            ['name' => 'Visit Visa', 'status' => 'active'],
            ['name' => 'Hajj & Umrah', 'status' => 'active'],
        ])->map(fn ($c) => Category::create($c));

        $agents = collect([
            ['name' => 'Md. Rahim Uddin', 'phone' => '01711-010101', 'email' => 'rahim.demo@example.com', 'address' => 'Uttara, Dhaka'],
            ['name' => 'Kamal Hossain', 'phone' => '01711-020202', 'email' => 'kamal.demo@example.com', 'address' => 'Mirpur, Dhaka'],
            ['name' => 'Shafiq Islam', 'phone' => '01711-030303', 'email' => 'shafiq.demo@example.com', 'address' => 'Chattogram'],
            ['name' => 'Nurul Amin', 'phone' => '01711-040404', 'email' => 'nurul.demo@example.com', 'address' => 'Sylhet'],
            ['name' => 'Jahangir Alam', 'phone' => '01711-050505', 'email' => 'jahangir.demo@example.com', 'address' => 'Khulna'],
        ])->map(fn ($a) => Agent::create($a));

        $officers = collect([
            ['name' => 'Officer Anwar', 'phone' => '01811-111111', 'email' => 'anwar.demo@example.com', 'status' => 'ACTIVE'],
            ['name' => 'Officer Selina', 'phone' => '01811-222222', 'email' => 'selina.demo@example.com', 'status' => 'ACTIVE'],
            ['name' => 'Officer Kabir', 'phone' => '01811-333333', 'email' => 'kabir.demo@example.com', 'status' => 'ACTIVE'],
        ])->map(fn ($o) => PassportOfficer::create($o));

        $employees = collect([
            ['name' => 'Tanvir Ahmed', 'designation' => 'Front Desk', 'phone' => '01911-111111', 'email' => 'tanvir.demo@example.com'],
            ['name' => 'Rasheda Begum', 'designation' => 'Document Officer', 'phone' => '01911-222222', 'email' => 'rasheda.demo@example.com'],
            ['name' => 'Mahmud Hasan', 'designation' => 'Processing Lead', 'phone' => '01911-333333', 'email' => 'mahmud.demo@example.com'],
            ['name' => 'Sabbir Rahman', 'designation' => 'Delivery Desk', 'phone' => '01911-444444', 'email' => 'sabbir.demo@example.com'],
        ])->map(fn ($e) => Employee::create($e));

        $agencies = collect([
            ['name' => 'Gulf Bridge Agency', 'category_id' => $categories[0]->id, 'contact_person' => 'Imran', 'phone' => '01511-111111', 'email' => 'gulf.demo@example.com', 'address' => 'Motijheel, Dhaka', 'status' => 'ACTIVE', 'notes' => 'Demo agency'],
            ['name' => 'Euro Study Circle', 'category_id' => $categories[1]->id, 'contact_person' => 'Priya', 'phone' => '01511-222222', 'email' => 'euro.demo@example.com', 'address' => 'Banani, Dhaka', 'status' => 'ACTIVE', 'notes' => 'Demo agency'],
            ['name' => 'Sky Travels', 'category_id' => $categories[2]->id, 'contact_person' => 'Farid', 'phone' => '01511-333333', 'email' => 'sky.demo@example.com', 'address' => 'Agrabad, Chattogram', 'status' => 'ACTIVE', 'notes' => 'Demo agency'],
        ])->map(fn ($g) => Agency::create($g));

        // ---------- Passports spread over the last 6 months ----------
        $firstNames = ['Abdul', 'Rina', 'Jashim', 'Mita', 'Rakib', 'Sumaiya', 'Alamin', 'Nusrat', 'Sohel', 'Farhana', 'Rashed', 'Lima'];
        $lastNames = ['Karim', 'Akter', 'Begum', 'Haque', 'Chowdhury', 'Islam', 'Sarker', 'Rahman'];
        $statuses = ['RECEIVED_FROM_AGENT', 'IN_PROCESS', 'COLLECTED_FROM_AGENCY'];
        $procStatuses = ['PENDING', 'IN_PROGRESS', 'DONE', 'DONE', 'DONE', 'REJECTED']; // DONE weighted

        $createdAt = function (int $monthsAgo, int $day) {
            return Carbon::now()->subMonths($monthsAgo)->setDay($day)->setTime(10, rand(0, 59));
        };

        $serial = 1;
        $createdIds = [];

        // 60 passports, ~10 per month
        for ($monthsAgo = 5; $monthsAgo >= 0; $monthsAgo--) {
            for ($i = 0; $i < 10; $i++) {
                $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
                $when = $createdAt($monthsAgo, rand(1, 27));

                $passport = Passport::create([
                    'agent_id' => $agents[array_rand($agents->all())]->id,
                    'passport_officer_id' => $officers[array_rand($officers->all())]->id,
                    'employee_id' => $employees[array_rand($employees->all())]->id,
                    'applicant_name' => $name,
                    'passport_number' => 'SW' . str_pad($serial++, 6, '0', STR_PAD_LEFT),
                    'phone' => '017' . rand(10000000, 99999999),
                    'address' => 'Dhaka, Bangladesh',
                    'date_of_birth' => Carbon::now()->subYears(rand(20, 45))->setDay(rand(1, 28)),
                    'issue_date' => $when->copy()->subYears(rand(1, 8)),
                    'expiry_date' => $when->copy()->addYears(rand(2, 8)),
                    'nationality' => 'Bangladeshi',
                    'nid_number' => '199' . rand(1000000000, 9999999999),
                    'status' => $statuses[array_rand($statuses)],
                    'notes' => null,
                    'created_at' => $when,
                    'updated_at' => $when,
                ]);

                $createdIds[] = [$passport->id, $when];
            }
        }

        // ---------- Processing for most passports ----------
        foreach ($createdIds as [$passportId, $when]) {
            if (rand(1, 100) > 85) {
                continue; // some passports have no processing yet
            }

            $status = $procStatuses[array_rand($procStatuses)];
            $procWhen = $when->copy()->addDays(rand(1, 10));

            PassportProcessing::create([
                'passport_id' => $passportId,
                'employee_id' => $employees[array_rand($employees->all())]->id,
                'agency_id' => rand(1, 100) > 25 ? $agencies[array_rand($agencies->all())]->id : null,
                'status' => $status,
                'notes' => 'Demo processing entry.',
                'created_at' => $procWhen,
                'updated_at' => $procWhen,
            ]);

            // Delivered ones get a collection record (some collected this month)
            if ($status === 'DONE' && rand(1, 100) <= 70) {
                $collectedAt = $procWhen->copy()->addDays(rand(2, 15));
                if ($collectedAt->isFuture()) {
                    $collectedAt = Carbon::now()->subDays(rand(0, 12));
                }

                PassportCollection::create([
                    'passport_id' => $passportId,
                    'employee_id' => $employees[3]->id,
                    'collected_at' => $collectedAt,
                    'notes' => 'Demo collection.',
                    'created_at' => $collectedAt,
                    'updated_at' => $collectedAt,
                ]);
            }
        }

        $this->command?->info('  Demo content seeded: ' . count($createdIds) . ' passports, processings & collections.');
    }
}

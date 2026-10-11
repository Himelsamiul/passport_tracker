<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Agency;
use App\Models\Agent;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Passport;
use App\Models\PassportCollection;
use App\Models\PassportOfficer;
use App\Models\PassportProcessing;
use App\Models\Registration;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 * ফিচার নোট (ডেটা রিফ্রেশ সিডার — মেজর রিসেট):
 * • লাইভ/লোকাল ডেটা পুরো রিফ্রেশ: সব পাসপোর্ট, প্রসেসিং, কলেকশন, এজেন্ট,
 *   এজেন্সি, অফিসার, কর্মী, ক্যাটাগরি, রেজিস্ট্রেশন, অ্যাক্টিভিটি লগ মুছে যায়।
 * • ইউজারদের মধ্যে শুধু মাস্টার অ্যাডমিন (shahriar.agroup@gmail.com) থাকে —
 *   বাকি সব অ্যাডমিন ডিলিট। মাস্টারের ক্রেডেনশিয়াল ফ্রেশ করে সেট হয়।
 * • এরপর RolesSeeder (Manager/Viewer) ও DemoContentSeeder (ডেমো ডেটা) চলে,
 *   যাতে ড্যাশবোর্ড আবার জীবন্ত থাকে।
 * • চালানোর নিয়ম: php artisan db:seed --class=DataRefreshSeeder --force
 *   (সাবধান — এটি ডেটা মুছে দেয়! লাইভে চালাতে GitHub Actions-এর
 *   "Refresh live data" workflow ব্যবহার করুন।)
 */

class DataRefreshSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->warn('  Refreshing ALL data — every module table is wiped, only the master admin survives.');

        Schema::disableForeignKeyConstraints();

        foreach ([
            'activity_logs',
            'passport_collections',
            'passport_processings',
            'passports',
            'registrations',
            'agencies',
            'passport_officers',
            'employees',
            'categories',
            'agents',
        ] as $table) {
            DB::table($table)->delete();
        }

        // All admins go, except the protected master account.
        User::where('email', '!=', User::MASTER_EMAIL)->delete();

        Schema::enableForeignKeyConstraints();

        // Master admin: fresh credentials, locked identity.
        $this->call(UserSeeder::class);
        $this->call(RolesSeeder::class);

        // Bring the dashboard back to life with a small show-off dataset.
        $this->call(DemoContentCompactSeeder::class);

        $this->command?->info('  Done. Master admin: ' . User::MASTER_EMAIL . ' — all other data refreshed.');
    }
}

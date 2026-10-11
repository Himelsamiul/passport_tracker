<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

/*
 * ফিচার নোট (প্রুন ওল্ড অ্যাডমিন সিডার):
 * • মাস্টার অ্যাডমিন (shahriar.agroup@gmail.com) বাদে বাকি সব প্যানেল ইউজার
 *   ডিলিট করে — পুরনো admin1…admin6 সিডার অ্যাকাউন্টগুলো লাইভ থেকে সরানোর জন্য।
 * • মডিউল ডেটা (পাসপোর্ট/প্রসেসিং/কলেকশন ইত্যাদি) কিছুই ছোঁয় না — শুধু ইউজার।
 * • মাস্টারের পাসওয়ার্ড/নাম আগের মতোই থাকে (শুধু না-থাকলে তৈরি হয়)।
 * • চালানো: php artisan db:seed --class=PruneOldAdminsSeeder --force
 */

class PruneOldAdminsSeeder extends Seeder
{
    public function run(): void
    {
        // Master must exist.
        $this->call(UserSeeder::class);

        $removed = User::where('email', '!=', User::MASTER_EMAIL)->delete();

        // Old accounts' audit entries keep their name snapshots; detach the
        // orphaned user_id links so the log stays consistent.
        ActivityLog::whereNotIn('user_id', User::select('id'))
            ->update(['user_id' => null]);

        $this->command?->info("  Pruned {$removed} non-master admin account(s). Master admin: " . User::MASTER_EMAIL);
    }
}

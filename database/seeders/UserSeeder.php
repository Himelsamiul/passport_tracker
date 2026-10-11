<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/*
 * ফিচার নোট (মাস্টার অ্যাডমিন সিডার):
 * • প্ল্যাটফর্মে একজনই মাস্টার অ্যাডমিন — shahriar.agroup@gmail.com (নাম: shah alom)।
 * • এই অ্যাকাউন্টের ইমেইল কখনো বদলানো যাবে না, ডিলিটও করা যাবে না
 *   (AdminUserController-এ লক করা)। এর রোল = Master Admin (role_id null = ফুল অ্যাকসেস)।
 * • অন্য সব ইউজার DataRefreshSeeder রিফ্রেশের সময় মুছে যায়।
 */

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => User::MASTER_EMAIL],
            [
                'name' => 'shah alom',
                'password' => Hash::make('Allaha@2030#'),
                'role_id' => null, // Master Admin — full access, cannot be downgraded
            ]
        );
    }
}

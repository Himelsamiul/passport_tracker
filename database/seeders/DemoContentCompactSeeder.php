<?php

namespace Database\Seeders;

/*
 * ফিচার নোট (কমপ্যাক্ট ডেমো সিডার — লাইভ "show-off" ডেটা):
 * • DemoContentSeeder-ই, শুধু প্রতি মাসে ২টি করে পাসপোর্ট (৬ মাসে ~১২টি) —
 *   লাইভে ড্যাশবোর্ড/চার্ট জীবন্ত রাখার জন্য অল্প ডেমো ডেটা।
 * • ডিরেক্টরি (এজেন্ট/অফিসার/কর্মী/ক্যাটাগরি/এজেন্সি) ছোট সেটই থাকে।
 * • পাসপোর্ট টেবিলে ডেটা থাকলে স্কিপ করে — আসল ডেটা কখনো নষ্ট করে না।
 */

class DemoContentCompactSeeder extends DemoContentSeeder
{
    protected function passportsPerMonth(): int
    {
        return 2;
    }
}

<?php
// 2025_10_21_211328_add_category_id_to_agencies_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Make sure the column exists and types match categories.id (unsigned BIGINT)
        if (!Schema::hasColumn('agencies', 'category_id')) {
            Schema::table('agencies', function (Blueprint $table) {
                $table->foreignId('category_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('categories')   // references id on categories
                      ->nullOnDelete();             // set null if category deleted
            });
        }
    }

    public function down(): void
    {
        // Drop FK + column only if the column exists; this avoids the 1091 error
        if (Schema::hasColumn('agencies', 'category_id')) {
            Schema::table('agencies', function (Blueprint $table) {
                // This drops the foreign key **and** the column safely
                $table->dropConstrainedForeignId('category_id');
            });
        }
    }
};

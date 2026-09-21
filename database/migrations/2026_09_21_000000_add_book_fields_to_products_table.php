<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('author')->nullable()->after('title');
            $table->string('category')->nullable()->after('author');
            $table->string('format')->default('PDF')->after('category');
            $table->unsignedInteger('pages_count')->nullable()->after('format');
            $table->string('language')->default('Français')->after('pages_count');
            $table->unsignedSmallInteger('publication_year')->nullable()->after('language');
            $table->string('sample_file')->nullable()->after('file_path');
            $table->string('isbn')->nullable()->after('chariow_product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'author',
                'category',
                'format',
                'pages_count',
                'language',
                'publication_year',
                'sample_file',
                'isbn',
            ]);
        });
    }
};

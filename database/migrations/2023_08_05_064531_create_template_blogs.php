<?php

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
        Schema::create('template_blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('slug', 100);
            $table->string('tags', 100)->nullable();
            $table->string('header_title', 100)->nullable();
            $table->string('category_name', 100)->nullable();
            $table->string('subcategory_name', 100)->nullable();
            $table->string('sub_subcategory_name', 100)->nullable();
            $table->string('template', 100)->nullable();
            $table->string('seller_name', 100)->default('TempDev');
            $table->text('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->text('youtube_iframe')->nullable();
            $table->text('header_content')->nullable();
            $table->string('meta_title', 100)->nullable();
            $table->string('meta_description', 200)->nullable();
            $table->string('facebook_meta_title', 100)->nullable();
            $table->string('facebook_meta_description', 200)->nullable();
            $table->string('twitter_meta_title', 100)->nullable();
            $table->string('twitter_meta_description', 200)->nullable();
            $table->tinyInteger('is_featured')->default(0)->nullable();
            $table->string('featured_image', 255)->default('default-featured-image.png');
            $table->string('featured_img_alt_text', 255)->nullable();
            $table->string('file', 255)->default('default-file.png');
            $table->string('og_image', 255)->default('default-og-image.png');
            $table->string('og_img_alt_text', 255)->nullable();
            $table->tinyInteger('status')->default(0)->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_blogs');
    }
};

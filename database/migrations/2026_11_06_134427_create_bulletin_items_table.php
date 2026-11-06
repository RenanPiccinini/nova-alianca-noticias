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
        Schema::create('bulletin_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bulletin_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('section')->default(1); // 1 or 2
            $table->unsignedSmallInteger('position')->default(1); // 1 to 4+
            $table->string('title', 300);
            $table->text('description');
            $table->string('image_url')->nullable();
            $table->string('source')->nullable()->default('Redação');
            $table->string('date_text')->nullable();
            $table->text('source_url')->nullable();
            $table->string('badge')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletin_items');
    }
};

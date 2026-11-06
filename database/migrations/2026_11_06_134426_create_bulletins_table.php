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
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title')->default('BOLETIM');
            $table->string('sub_title')->default('PORTAL NOVA ALIANÇA');
            $table->string('tagline')->default('INFORMAÇÃO • CIDADANIA • ACONTECEU VIROU NOTÍCIA');
            $table->date('edition_date');
            $table->string('edition_time')->default('EDIÇÃO DO DIA');
            $table->string('badge_text')->nullable()->default('24 HORAS NO AR');
            $table->string('header_location')->default('GOIÂNIA & REGIÃO');

            // Section 1
            $table->string('section_1_title')->default('CIDADE & REGIÃO');
            $table->string('section_1_color')->default('#0a369d');

            // Section 2
            $table->string('section_2_title')->default('ESTADO & BRASIL');
            $table->string('section_2_color')->default('#028090');

            // Footer info
            $table->string('footer_title')->default('PORTAL DE NOTÍCIAS NOVA ALIANÇA');
            $table->string('footer_slogan')->default('Aconteceu virou notícia.');
            $table->string('footer_whatsapp')->nullable()->default('(62) 99999-9999');
            $table->string('footer_instagram')->nullable()->default('@portalnovaalianca');
            $table->string('footer_website')->nullable()->default('www.portalnovaalianca.com.br');
            $table->text('footer_editorial_note')->nullable();

            $table->string('theme_style')->default('classic_blue');
            $table->string('status')->default('published');
            $table->string('slug')->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletins');
    }
};

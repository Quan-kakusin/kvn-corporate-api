<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('service_translations', function (Blueprint $table) {
            $table->id();
            // Khóa ngoại liên kết với bảng services (xóa service thì bản dịch cũng bay màu theo)
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');

            $table->string('locale', 10);
            $table->string('name');
            $table->text('short_description')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_translations');
    }
};

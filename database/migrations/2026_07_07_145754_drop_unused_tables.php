<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Xóa các bảng chứa khóa ngoại (translations) trước
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('service_translations');
        Schema::dropIfExists('wp_post_translations');

        // 2. Sau đó mới xóa các bảng gốc
        Schema::dropIfExists('products');
        Schema::dropIfExists('services');
    }

    public function down(): void
    {
        // Để trống vì chúng ta không có nhu cầu khôi phục lại các bảng rác này
    }
};

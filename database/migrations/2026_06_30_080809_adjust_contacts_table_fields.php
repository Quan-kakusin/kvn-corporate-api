<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('company')->nullable();

            // Kiểm tra: Có cột 'message' thì mới rename,
            // Không có 'message' và chưa có 'content' thì tạo luôn cột 'content'.
            if (Schema::hasColumn('contacts', 'message')) {
                $table->renameColumn('message', 'content');
            } elseif (!Schema::hasColumn('contacts', 'content')) {
                $table->text('content')->nullable(); // Bro có thể đổi 'text' thành 'string' tùy thiết kế
            }

            // Xóa after() đi cho an toàn, cứ để nó nối vào cuối bảng
            $table->boolean('agree')->default(false);
        });
    }

    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['company', 'agree']);

            // Rollback an toàn
            if (Schema::hasColumn('contacts', 'content')) {
                $table->renameColumn('content', 'message');
            }
        });
    }
};

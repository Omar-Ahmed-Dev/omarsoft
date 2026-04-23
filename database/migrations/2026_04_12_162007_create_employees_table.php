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
        Schema::create('employees', function (Blueprint $table) {
            // 1. الرقم التعريفي الفريد لكل موظف
            $table->id();

            // 2. اسم الموظف (نص)
            $table->string('name');

            // 3. الوظيفة (نص ويقبل يكون فاضي)
            $table->string('title')->nullable();

            // 4. مسار الصورة (نص ويقبل يكون فاضي)
            $table->string('img')->nullable();

            // 5. رقم المدير (pid) - لازم يكون نفس نوع الـ id الأساسي
            $table->unsignedBigInteger('pid')->nullable();

            // 6. التوقيتات (createdAt - updatedAt) لارفيل بيعملهم أوتوماتيك
            $table->timestamps();

            // 7. الربط المنطقي: بنقوله إن الـ pid "مفتاح أجنبي" راجع لنفس الجدول
            $table->foreign('pid')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade'); // لو مسحنا مدير، الموظفين اللي تحته يتمسحوا (اختياري)
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

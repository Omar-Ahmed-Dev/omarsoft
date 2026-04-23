<?php

use Illuminate\Http\Request;

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

// رابط جلب كل الموظفين (عشان الشجرة تترسم)
Route::get('/employees', [EmployeeController::class, 'index']);

// رابط إضافة موظف جديد
Route::post('/employees', [EmployeeController::class, 'store']);
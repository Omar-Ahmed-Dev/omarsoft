<?php

namespace App\Services;

use App\Models\Employee;

class EmployeeService
{
    /**
     * وظيفة إضافة موظف جديد
     */
    public function createEmployee(array $data)
    {
        // بنستخدم الموديل عشان نخزن البيانات في الداتا بيز
        return Employee::create([
            'name'  => $data['name'],
            'title' => $data['title'],
            'img'   => $data['img'] ?? 'https://cdn.balkan.app/shared/3.jpg', // صورة افتراضية لو مبعتش
            'pid'   => $data['pid'],
        ]);
    }

    // جوه ملف الـ EmployeeService

public function getAllEmployees()
{
    // بنجيب كل الموظفين من الداتا بيز
    // وممكن نستخدم orderBy عشان نضمن إن الكبير يظهر الأول مثلاً
    return Employee::all();
}

public function deleteEmployee($id)
{
    // 1. بنجيب الموظف اللي عليه العين
    $employee = Employee::find($id);

    if (!$employee) {
        return false; 
    }

    // 2. بنعرف مين "المدير اللي فوقيه" (عشان نودي له العيال)
    $parentId = $employee->pid; 

    // 3. توريث الموظفين (خطة الإنقاذ)
    // بنقول للداتا بيز: أي حد كان تايع للشخص اللي هيتمسح (pid == id)
    // خليه دلوقتي يتبع المدير اللي فوق (parentId)
    Employee::where('pid', $id)->update(['pid' => $parentId]);

    // 4. دلوقتى نمسح الموظف وإحنا مطمنين إن شجرته متنقلتش للشارع
    return $employee->delete();
}



public function updateEmployee($id, $data)
{
    // 1. بندور على الموظف اللي عايزين نعدل بياناته
    $employee = Employee::find($id);

    if (!$employee) {
        return false; // لو الموظف مش موجود لأي سبب
    }

    // 2. بنحدث البيانات اللي جاية من الـ Request
    // الـ $data دي شايلة (name و title) اللي كتبناهم في الـ SweetAlert
    return $employee->update([
        'name'  => $data['name'],
        'title' => $data['title'],
        // لو في بيانات تانية زي الصورة ممكن تضاف هنا
    ]);
}

public function saveVantaTheme(string $theme): void
{
    auth()->user()->update([
        'vanta_theme' => $theme
    ]);
}

}



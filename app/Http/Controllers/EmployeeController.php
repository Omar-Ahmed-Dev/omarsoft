<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use App\Services\UserService;

class EmployeeController extends Controller
{
    protected $employeeService;
    protected $userService;

    public function __construct(EmployeeService $employeeService, UserService $userService)
    {
        $this->employeeService = $employeeService;
        $this->userService = $userService;
    }
    /**
     * عرض كل الموظفين (عشان الشجرة تترسم)
     */
    public function index()
    {
        $employees = $this->employeeService->getAllEmployees();

        // بنرجع البيانات كـ JSON عشان الـ JavaScript يفهمها
        return response()->json($employees);
    }

    /**
     * حفظ موظف جديد
     */
    public function store(StoreEmployeeRequest $request)
    {
        // بناخد البيانات اللي اتعملها Validation بس
        $data = $request->validated();

        // بننادي على السيرفيس تعمل الشغل التقيل (الحفظ)
        $employee = $this->employeeService->createEmployee($data);

        // بنرد برسالة نجاح وبيانات الموظف الجديد
        return response()->json([
            'message' => 'تم إضافة الموظف بنجاح يا عمر!',
            'employee' => $employee
        ], 201);
    }



    public function destroy($id, EmployeeService $employeeService)
    {
        // بنكلم السيرفس ونقولها امسحي يا بطلة
        $isDeleted = $employeeService->deleteEmployee($id);

        if ($isDeleted) {
            return response()->json(['success' => true, 'message' => 'تم الحذف بنجاح']);
        }

        return response()->json(['success' => false, 'message' => 'فشل في الحذف'], 400);
    }
    public function update(Request $request, $id, EmployeeService $employeeService)
    {
        // بنبعت البيانات للسيرفيس زي ما هي
        $isUpdated = $employeeService->updateEmployee($id, $request->all());

        if ($isUpdated) {
            // بنرجع لورا (لصفحة الشجرة) وبنبعت رسالة "نجاح" في السيشن
            return back()->with('success', 'تم التعديل بنجاح يا بطل!');
        }

        return back()->with('error', 'حصلت مشكلة في التعديل');
    }

    public function saveVantaTheme(Request $request)
    {
        $this->userService->saveVantaTheme($request->vanta_theme);
        return response()->json(['success' => true]);
    }
}

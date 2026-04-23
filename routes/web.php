<?php
use App\Http\Controllers\flightsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRegistrationController; // لازم نستدعي الكنترولر هنا كمان
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return view('css_tree', [
        'employees' => App\Models\Employee::all(),
        'user' => App\Models\User::first()
    ]);
});

Route::post('/save-theme', [UserController::class, 'saveTheme']);

// روت حذف الموظف (طريقة الـ Web)
// اتأكد إن الـ slash والترتيب صح
Route::post('/employees/delete/{id}', [EmployeeController::class, 'destroy']);
// استخدمنا PUT أو PATCH لأننا بنعمل تحديث لبيانات موجودة
Route::put('/employees/{id}', [EmployeeController::class, 'update']);

Route::post('/save-vanta-theme', [EmployeeController::class, 'saveVantaTheme']);

//  هنا ممكن احدد نوع الداتا اللي هتيجي في الروت عن طريق الريجيكس زي المثال اللي تحت ده
// Route::get('/omar/{name}/{age}', function ( $name, $age) {
//     return 'welcome '.$name.' your age is '.$age;
// })->where(['name'=>'[a-zA-Z]+','age'=>'[0-9]+']);   





// Route::get('/omar/name/{name}', function ( $name) {
//     return 'welcome '.$name;
// });

// Route::get('/omar/age/{age}', function ( $age) {
//     return 'welcome your age is '.$age;
// });


// بجمع الروتات اللي ليها نفس البريفكس فبالتالي ممكن اجمعهم في جروب واحد زي المثال اللي تحت ده

// Route::prefix('omar')->group(function () {
    
// Route::get('name/{name}', function ( $name) {
//     return 'welcome '.$name;
// });

// Route::get('age/{age}', function ( $age) {
//     return 'welcome your age is '.$age;
// });

// });


// ده رساله الخطأ اللي هيظهر لما ادخل على روت مش موجود

// Route:: fallback(function () {
//     return 'A7A';
// });


// Route::get('/asd/{name}', function ( $name) {
//     return 'your name is '.$name ;
// });

// ده عباره عن  روت بيربط بين الريكوست والكونترولر
// و اقدر اني ارجع فيو يعني صففحة تظهر لي المستخدم عن طريق الكونترولر
// Route::get('asd1' , [UserController::class, 'asd1']);

// Route::get('login/', [UserController::class, 'login']);
// Route::get('master/', function () {
//     return view('master');
// });
// Route::get('atrcal/', function () {
//     return view('atrcal');
// });




// دي اختبارات علي ما اتعلمته
// هنا بعمل روت بسيط بيرجع لي رسالة ترحيب
// Route::get('hi', function () {
//     return ("welcome omar");
// });
// // هنا بعمل روت بياخد باراميتر من نوع نصي و بيرجع لي رسالة ترحيب بالاسم اللي اتاخد و كمان اعمل قيمه افتراضيه في حاله عدم ارسال اسم
// Route::get('your name/{name?}', function ($name) {
//     return ("welcome ".$name);    
// });

// // لو عايز ارجع صفحهة فيو بدل ما ارجع رساله بسيطه
// Route::get('my view', function () {
//     return view('omar');
// });
// // هنا بعمل روت بياخد باراميتر من نوع عددي و بيرجع لي رسالة ترحيب بالاسم و العمر اللي اتاخد
// Route::get('your name and age/{name}/{age}', function ($name , $age) {
//     return ("welcome ".$name." your age is ".$age);    
// });

// // هنا بعمل روت بيربط بين الريكوست و الكونترولر عشان يرجع لي صفحه فيو فيها بيانات من جدول في الداتا بيز

// Route::get('flights', [flightsController::class, 'index']);
// Route::get('create_flights', [flightsController::class, 'create'])->name('create_flights');
// Route::post('store_flights', [flightsController::class, 'store'])->name('store_flights');
// Route::get('edit_flights/{id}', [flightsController::class, 'edit'])->name('edit_flights');
// Route::post('update_flights/{id}', [flightsController::class, 'update_flights'])->name('update_flights');
// Route::get('delete_flights/{id}', [flightsController::class, 'delete_flights'])->name('delete_flights');



// 1. روت لعرض صفحة التسجيل (الـ View)
// Route::get('/register', function () {
//     return view('register');
// });

// Route::post('/register/save', [UserRegistrationController::class, 'store']);

// // روت عرض جدول المستخدمين
// Route::get('/users', [UserRegistrationController::class, 'showAll'])->middleware('auth');

// // الروت اللي بيفتح صفحة اللوجن
// Route::get('/login', [UserRegistrationController::class, 'showLogin']) ->name('login');

// // الروت اللي بيستقبل البيانات وبيشيك عليها
// Route::post('/login/check', [UserRegistrationController::class, 'auth']);
// // رابط الخروج من النظام
// // روت تسجيل الخروج
// Route::post('/logout', [UserRegistrationController::class, 'logout'])->middleware('auth');


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
// 1. الخانات اللي مسموح لارفيل يكتب فيها (عشان الحماية)
    protected $fillable = ['name', 'title', 'img', 'pid'];

    // 2. علاقة الموظف بمديره (الأب)
    // بنقول إن الموظف ده "ينتمي لـ" موظف تاني من خلال الـ pid
    public function parent()
    {
        return $this->belongsTo(Employee::class, 'pid');
    }

    // 3. علاقة الموظف بالناس اللي تحت إيده (الأبناء)
    // بنقول إن الموظف ده "عنده كتير" موظفين شغالين تحته
    public function children()
    {
        return $this->hasMany(Employee::class, 'pid');
    }
    
}


// 1. تعريف الشجرة الأساسي
var chart = new OrgChart(document.getElementById("tree"), {
    template: "belinda",
    nodeBinding: {
        field_0: "name",
        field_1: "title",
        img_0: "img"
    },
    nodes: [employees]
});

// 2. دالة جلب البيانات من السيرفر عند تحميل الصفحة
// function loadEmployees() {
//     fetch('/api/employees')
//         .then(response => response.json())
//         .then(data => {
//             chart.load(data);
//         })
//         .catch(error => {
//             console.error('يا عمر حصل مشكلة وأنا بجيب البيانات:', error);
//         });
// }

// تشغيل الجلب فوراً
// loadEmployees();

// 3. مراقب الضغط على العناصر (لفتح موديل الإضافة)
document.getElementById('tree').addEventListener('click', function(event) {
    var nodeElement = event.target.closest('[data-n-id]');
    if (nodeElement) {
        var employeeId = nodeElement.getAttribute('data-n-id');
        document.getElementById('parent_id').value = employeeId;
        document.getElementById('addNodeModal').style.display = 'block';
        document.getElementById('modalOverlay').style.display = 'block';
    }
});

// 4. دالة قفل الموديل وتصفير الخانات
function closeModal() {
    document.getElementById('addNodeModal').style.display = 'none';
    document.getElementById('modalOverlay').style.display = 'none';
    document.getElementById('new_name').value = "";
    document.getElementById('new_title').value = "";
}

// 5. دالة حفظ الموظف الجديد (Sending Data to Laravel)
function saveNode() {
    var name = document.getElementById('new_name').value;
    var title = document.getElementById('new_title').value;
    var pid = document.getElementById('parent_id').value;

    if (name === "") {
        alert("يا عمر لازم تكتب اسم الموظف!");
        return;
    }

    var employeeData = {
        name: name,
        title: title,
        pid: pid,
        img: "https://cdn.balkan.app/shared/3.jpg" 
    };

    fetch('/api/employees', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            // بنسحب الـ Token من الميتا تاج اللي في الهيدر للأمان
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(employeeData)
    })
    .then(response => {
        if (!response.ok) throw response;
        return response.json();
    })
    .then(data => {
        console.log(data.message);
        chart.addNode(data.employee); // إضافة النود الجديدة للشجرة فوراً
        closeModal();
    })
    .catch(error => {
        console.error('فيه غلط حصل وأنا بخزن يا عمر:', error);
        alert("حصلت مشكلة أثناء الحفظ، جرب تاني.");
    });
}
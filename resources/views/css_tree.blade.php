<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شجرة الموظفين - العرض من الداتا بيز</title>

    <meta http-equiv="Content-Security-Policy"
        content="script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://balkan.app https://cdnjs.cloudflare.com;">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script>
        window.THREE = THREE;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/tree-style.css') }}">
    <script src="https://balkan.app/js/orgchart.js"></script>
    <script src="https://balkan.app/js/export.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.birds.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.fog.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.clouds.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.clouds2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.cells.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.trunk.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.topology.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.dots.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.rings.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.halo.min.js"></script>
</head>

<body>
    <div id="vanta-bg" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;"></div>
    <div id="tree"></div>

    <div id="addNodeModal" style="display:none;">
        <h3>إضافة موظف جديد</h3>
        <input type="text" id="new_name" placeholder="اسم الموظف">
        <input type="text" id="new_title" placeholder="الوظيفة">
        <input type="hidden" id="parent_id">
        <div class="modal-buttons">
            <button onclick="saveNode()" class="btn btn-save">حفظ</button>
            <button onclick="closeModal()" class="btn btn-cancel">إلغاء</button>
        </div>
    </div>

    <div id="modalOverlay" style="display:none;"></div>

    <nav class="menu" aria-label="Main Menu">
        <input type="checkbox" id="toggle" name="cb" class="visually-hidden" />
        <label for="toggle" aria-controls="menu-list" aria-label="Open/Close Menu">
            <span class="burger" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </label>
        <ul class="menu-list">
            <li style="--i: 0">
                <a href="#" aria-label="Home" id="theme-btn">
                    <span class="icon">📑 قوالب</span>
                </a>
            </li>
            <li style="--i: 1">
                <a href="#" onclick="chart.exportToPDF();" aria-label="Export PDF">
                    <span class="icon">📄 PDF</span>
                </a>
            </li>
            <li style="--i: 2">
                <a href="#" onclick="exportCSV()" aria-label="Save CSV">
                    <span class="icon">📊 CSV</span>
                </a>
            </li>
            <li style="--i: 3">
                <a href="#" onclick="chart.exportToSVG()" aria-label="Export SVG">
                    <span class="icon">🖼️ SVG</span>
                </a>
            </li>
            <li style="--i: 4">
                <a href="#" aria-label="Background Theme" id="vanta-btn">
                    <span class="icon">🎨 ثيم</span>
                </a>
            </li>
        </ul>
    </nav>

    <script>
        // 1. بيانات الموظفين
        const employees = @json($employees);
        console.log('بيانات الموظفين من الداتا بيز:', employees);

        // 2. إعداد الشجرة
        var chart = new OrgChart(document.getElementById("tree"), {
            template: "{{ $user->tree_theme ?? 'belinda' }}",
            nodeBinding: {
                field_0: "name",
                field_1: "title",
                img_0: "img"
            },
            nodes: [
                @foreach ($employees as $employee)
                    {
                        id: {{ $employee->id }},
                        name: "{{ $employee->name }}",
                        title: "{{ $employee->title }}",
                        img: "{{ $employee->img }}",
                        pid: {{ $employee->pid ?? 'null' }}
                    },
                @endforeach
            ],
            menuButton: {
                text: "إضافة",
                onClick: function(nodeId) {
                    $('#parent_id').val(nodeId);
                    $('#addNodeModal, #modalOverlay').show();
                }
            }
        });

        // 3. كليك يمين
        $('#tree').on('contextmenu', function(event) {
            event.preventDefault();
            var nodeElement = $(event.target).closest('[data-n-id]');
            if (nodeElement.length) {
                var employeeId = nodeElement.attr('data-n-id');
                $('#context-menu').remove();
                var menu = $('<div id="context-menu">').css({
                    left: event.clientX + 'px',
                    top: event.clientY + 'px'
                }).html(`
                <button class="context-btn" style="background:#f44336;" onclick="deleteEmployee(${employeeId})">🗑️</button>
                <button class="context-btn" style="background:#2196F3;" onclick="editEmployee(${employeeId})">✏️</button>
                <button class="context-btn" style="background:#4CAF50;" onclick="addEmployee(${employeeId})">➕</button>
            `);
                $('body').append(menu);
                menu.find('.context-btn').each(function(index) {
                    var btn = $(this);
                    setTimeout(function() {
                        btn.addClass('show');
                    }, index * 100);
                });
                $(document).one('click', function() {
                    $('#context-menu').remove();
                });
            }
        });

        // 4. إغلاق الموديل
        function closeModal() {
            $('#addNodeModal, #modalOverlay').hide();
            $('#new_name, #new_title').val('');
        }

        // 5. حفظ موظف من الموديل
        function saveNode() {
            var name = $('#new_name').val();
            var title = $('#new_title').val();
            var pid = $('#parent_id').val();
            if (name === '') {
                alert('يا عمر لازم تكتب اسم الموظف!');
                return;
            }
            $.ajax({
                url: '/api/employees',
                method: 'POST',
                contentType: 'application/json',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify({
                    name: name,
                    title: title,
                    pid: pid,
                    img: 'https://cdn.balkan.app/shared/3.jpg'
                }),
                success: function(data) {
                    chart.addNode(data.employee);
                    closeModal();
                },
                error: function(err) {
                    console.error('غلط في التخزين يا عمر:', err);
                    alert('حصلت مشكلة أثناء الحفظ.');
                }
            });
        }

        // 6. تغيير ثيم الشجرة
        $('#theme-btn').on('click', function() {
            Swal.fire({
                title: 'اختر قالب الشجرة',
                input: 'select',
                inputOptions: {
                    "ana": "Ana",
                    "olivia": "Olivia",
                    "diva": "Diva",
                    "mila": "Mila",
                    "polina": "Polina",
                    "mery": "Mery",
                    "rony": "Rony",
                    "belinda": "Belinda",
                    "ula": "Ula",
                    "isla": "Isla",
                    "deborah": "Deborah",
                    "clara": "Clara"
                },
                confirmButtonText: 'تغيير الثيم',
                cancelButtonText: 'الغاء',
                showCancelButton: true,
            }).then(function(result) {
                if (result.isConfirmed) {
                    var form = $('<form>').attr({
                        method: 'POST',
                        action: '/save-theme'
                    });
                    form.append($('<input>').attr({
                        type: 'hidden',
                        name: '_token'
                    }).val($('meta[name="csrf-token"]').attr('content')));
                    form.append($('<input>').attr({
                        type: 'hidden',
                        name: 'tree_theme'
                    }).val(result.value));
                    $('body').append(form);
                    form.submit();
                }
            });
        });

        // 7. إضافة موظف بـ SweetAlert
        function addEmployee(nodeId) {
            Swal.fire({
                title: 'إضافة موظف جديد',
                html: `
                <div style="margin-top: 10px;">
                    <label style="display: block; text-align: right; margin-bottom: 5px;">اسم الموظف:</label>
                    <input id="swal-name" class="swal2-input" style="margin: 0; width: 80%;" placeholder="الاسم...">
                </div>
                <div style="margin-top: 15px;">
                    <label style="display: block; text-align: right; margin-bottom: 5px;">الوظيفة:</label>
                    <input id="swal-title" class="swal2-input" style="margin: 0; width: 80%;" placeholder="المسمى الوظيفي...">
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'حفظ الموظف',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#4CAF50',
                cancelButtonColor: '#d33',
                preConfirm: () => {
                    const name = $('#swal-name').val();
                    const title = $('#swal-title').val();
                    if (!name) {
                        Swal.showValidationMessage('يا عمر لازم تكتب اسم الموظف!');
                        return false;
                    }
                    const randomId = Math.floor(Math.random() * 10) + 1;
                    return {
                        name,
                        title,
                        pid: nodeId,
                        img: `https://cdn.balkan.app/shared/${randomId}.jpg`
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.showLoading();
                    $.ajax({
                        url: '/api/employees',
                        method: 'POST',
                        contentType: 'application/json',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: JSON.stringify(result.value),
                        success: function(data) {
                            chart.addNode(data.employee);
                            Swal.fire({
                                icon: 'success',
                                title: 'تمت الإضافة!',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        },
                        error: function() {
                            Swal.fire('فشل الحفظ', 'حصلت مشكلة في الاتصال بالسيرفر.', 'error');
                        }
                    });
                }
            });
        }

        // 8. حذف موظف
        function deleteEmployee(id) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'الموظف ده هيتمسح نهائياً!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'أيوه، امسحه!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/employees/delete/${id}`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        success: function(data) {
                            if (data.success) {
                                chart.removeNode(id);
                                Swal.fire('تم الحذف!', 'اتمسح بنجاح.', 'success');
                            } else {
                                Swal.fire('فشل!', 'حصلت مشكلة أثناء المسح.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('غلط!', 'مش قادر أتواصل مع السيرفر.', 'error');
                        }
                    });
                }
            });
        }

        // 9. تعديل موظف
        function editEmployee(id) {
            var nodeData = chart.get(id);
            Swal.fire({
                title: 'تعديل بيانات الموظف',
                html: `
                <div style="margin-top: 10px;">
                    <label style="display: block; text-align: right;">الاسم</label>
                    <input id="swal-name" class="swal2-input" value="${nodeData.name}">
                </div>
                <div style="margin-top: 15px;">
                    <label style="display: block; text-align: right;">الوظيفة</label>
                    <input id="swal-title" class="swal2-input" value="${nodeData.title}">
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'حفظ التعديلات',
                preConfirm: () => ({
                    name: $('#swal-name').val(),
                    title: $('#swal-title').val()
                })
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = $('<form>').attr({
                        action: `/employees/${id}`,
                        method: 'POST'
                    });
                    form.append($('<input>').attr({
                        type: 'hidden',
                        name: '_token'
                    }).val($('meta[name="csrf-token"]').attr('content')));
                    form.append($('<input>').attr({
                        type: 'hidden',
                        name: '_method'
                    }).val('PUT'));
                    form.append($('<input>').attr({
                        type: 'hidden',
                        name: 'name'
                    }).val(result.value.name));
                    form.append($('<input>').attr({
                        type: 'hidden',
                        name: 'title'
                    }).val(result.value.title));
                    $('body').append(form);
                    form.submit();
                }
            });
        }
    </script>

    <script>
        // رسائل النجاح والخطأ
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'عاش يا وحش',
                text: "{{ session('success') }}",
                timer: 2500,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'أوبس..',
                text: "{{ session('error') }}",
            });
        @endif

        // دوال التصدير
        function exportCSV() {
            chart.exportCSV({
                filename: 'Employees_List.csv'
            });
        }

        // متغير الخلفية
        let vantaEffect = null;

        // دالة اختيار الخلفية
        function openVantaMenu() {
            Swal.fire({
                title: 'اختر شكل الخلفية المتحركة',
                input: 'select',
                inputOptions: {
                    "birds": "الطيور (Birds)",
                    "fog": "الضباب (Fog)",
                    "waves": "الموجات (Waves)",
                    "clouds": "السحب (Clouds)",
                    "clouds2": "السحب 2 (Clouds2)",
                    "globe": "الكرة الأرضية (Globe)",
                    "net": "الشبكة (Net)",
                    "cells": "الخلايا (Cells)",
                    "trunk": "الجذوع (Trunk)",
                    "topology": "التضاريس (Topology)",
                    "dots": "النقط (Dots)",
                    "rings": "الحلقات (Rings)",
                    "halo": "الهالة (Halo)"
                },
                confirmButtonText: 'تطبيق',
                cancelButtonText: 'إلغاء',
                showCancelButton: true,
                confirmButtonColor: '#2196f3'
            }).then(function(result) {
                if (result.isConfirmed && result.value) {
                    if (vantaEffect) {
                        try {
                            vantaEffect.destroy();
                        } catch (err) {
                            console.warn(err);
                        }
                        $('#vanta-bg').empty();
                    }
                    vantaEffect = VANTA[result.value.toUpperCase()]({
                        el: '#vanta-bg',
                        mouseControls: true,
                        touchControls: true,
                        gyroControls: false,
                        minHeight: 200,
                        minWidth: 200,
                        scale: 1,
                        scaleMobile: 1,
                        color: 0x2196f3,
                        backgroundColor: 0x232323
                    });
                    $.ajax({
                        url: '/save-vanta-theme',
                        method: 'POST',
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: JSON.stringify({
                            vanta_theme: result.value
                        })
                    });
                }
            });
        }

        // ربط زرار الثيم
        $('#vanta-btn').on('click', function(e) {
            e.preventDefault();
            openVantaMenu();
        });

        // السحب والإفلات للمنيو
        var isDragging = false,
            offsetX, offsetY;
        var $menu = $('.menu');

        $menu.on('mousedown', function(e) {
            isDragging = true;
            offsetX = e.clientX - $menu.offset().left;
            offsetY = e.clientY - $menu.offset().top;
            $menu.css('cursor', 'grabbing');
        });

        $(document).on('mousemove', function(e) {
            if (!isDragging) return;
            $menu.css({
                right: 'auto',
                left: (e.clientX - offsetX) + 'px',
                top: (e.clientY - offsetY) + 'px'
            });
        });

        $(document).on('mouseup', function() {
            isDragging = false;
            $menu.css('cursor', 'grab');
        });

        // تشغيل الخلفية المحفوظة عند فتح الصفحة
        const savedVanta = "{{ \App\Models\User::first()->vanta_theme ?? 'none' }}";
        if (savedVanta !== 'none') {
            $(document).ready(function() {
                setTimeout(function() {
                    vantaEffect = VANTA[savedVanta.toUpperCase()]({
                        el: '#vanta-bg',
                        THREE: THREE,
                        mouseControls: true,
                        touchControls: true,
                        gyroControls: false,
                        minHeight: 200,
                        minWidth: 200,
                        scale: 1,
                        scaleMobile: 1,
                        color: 0x2196f3,
                        backgroundColor: 0x232323
                    });
                }, 500);
            });
        }
    </script>

</body>

</html>

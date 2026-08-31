@extends('layouts.master')
@section('title', 'طلبات التسجيل الذاتي')
<style>
    #selfRegistrationLinkModal .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }
    #selfRegistrationLinkModal .reg-link-header {
        background: linear-gradient(135deg, #740e0e 0%, #9a1e1e 100%);
        color: #fff;
        padding: 20px 44px 22px 44px;
        position: relative;
        min-height: auto;
    }
    #selfRegistrationLinkModal .reg-link-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.15rem;
        line-height: 1.5;
        white-space: normal;
    }
    #selfRegistrationLinkModal .reg-link-header p {
        margin: 8px 0 0;
        font-size: 0.85rem;
        line-height: 1.7;
        opacity: 0.92;
        white-space: normal;
        word-wrap: break-word;
    }
    #selfRegistrationLinkModal .reg-link-header .btn-close {
        position: absolute;
        left: 16px;
        top: 20px;
        filter: brightness(0) invert(1);
        opacity: 0.85;
    }
    #selfRegistrationLinkModal .reg-link-body {
        padding: 24px;
        background: #F8F9FB;
    }
    #selfRegistrationLinkModal .project-select-wrap label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #555;
        margin-bottom: 6px;
        display: block;
    }
    #selfRegistrationLinkModal .project-select-wrap select {
        border-radius: 10px;
        padding: 10px 14px;
        border: 1px solid #e0e0e0;
        font-weight: 600;
    }
    #selfRegistrationLinkModal .role-card {
        background: #fff;
        border-radius: 14px;
        padding: 14px 16px;
        margin-top: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #eee;
    }
    #selfRegistrationLinkModal .role-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    #selfRegistrationLinkModal .role-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 30px;
        color: #fff;
    }
    #selfRegistrationLinkModal .role-chip.shelf_stacker { background: #2a9d8f; }
    #selfRegistrationLinkModal .role-chip.supervisor { background: #e07a2c; }
    #selfRegistrationLinkModal .role-chip.area_manager { background: #5b5fc7; }
    #selfRegistrationLinkModal .role-chip.project_manager { background: #740e0e; }
    #selfRegistrationLinkModal .role-card.pm-link {
        border: 1px dashed #740e0e;
        background: #fff8f8;
    }
    #selfRegistrationLinkModal .divider-label {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #999;
        font-size: 0.78rem;
        font-weight: 600;
        margin: 22px 0 10px;
    }
    #selfRegistrationLinkModal .divider-label::before,
    #selfRegistrationLinkModal .divider-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e3e3e3;
    }
    #selfRegistrationLinkModal .role-link-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    #selfRegistrationLinkModal .role-link-input {
        flex: 1;
        background: #F5F6F8;
        border: 1px dashed #d8d8d8;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.82rem;
        color: #555;
        direction: ltr;
        text-align: left;
        font-family: monospace;
    }
    #selfRegistrationLinkModal .copy-btn {
        background: #740e0e;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.82rem;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        transition: background 0.2s ease;
    }
    #selfRegistrationLinkModal .copy-btn:hover { background: #5d0a0a; color: #fff; }
    #selfRegistrationLinkModal .copy-btn.copied { background: #2a9d8f; }
</style>
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-2 position-relative z-index-2 d-flex align-items-center justify-content-between w-100">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 flex-grow-1">
                        <h6 class="text-black text-capitalize ps-3 mb-0" style="font-size:25px; font-weight:800;color: #000;">
                            طلبات التسجيل الذاتي
                        </h6>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('employees.index') }}" class="btn btn-icon-only" title="عودة لقائمة الموظفين">
                            <i class="fas fa-arrow-right feature-icon"></i>
                        </a>
                        <a href="{{ route('employees.template') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            تحميل نموذج الموظفين
                        </a>
                        @if (($role && $role->hasPermissionTo('add_employee')) || Auth::user()->role === 'admin')
                            <button class="btn btn-purple" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">
                                <i class="fas fa-plus"></i> إضافة موظف
                            </button>
                            @if (!empty($projects))
                                <button class="btn btn-outline-purple" data-bs-toggle="modal" data-bs-target="#selfRegistrationLinkModal">
                                    <i class="fas fa-link"></i> رابط تسجيل الموظفين
                                </button>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        @if (count($employees) > 0)
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">الاسم</th>
                                        <th class="text-center">المشروع</th>
                                        <th class="text-center">الدور</th>
                                        <th class="text-center">تاريخ التقديم</th>
                                        <th class="text-center">القائمة السوداء</th>
                                        <th class="text-center">الحالة / الإجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                            <td class="align-middle text-center">
                                                <a href="{{ route('employees.show', $employee['id']) }}">
                                                    {{ $employee['name'] }}
                                                </a>
                                            </td>
                                            <td class="align-middle text-center">{{ $employee['project'] ?? '-' }}</td>
                                            <td class="align-middle text-center">{{ $employee['role'] }}</td>
                                            <td class="align-middle text-center">{{ $employee['submitted_at'] ?? '-' }}</td>
                                            <td class="align-middle text-center">
                                                @if ($employee['is_blacklisted'])
                                                    <span class="badge bg-danger" title="تطابقت بياناته مع موظف في القائمة السوداء">
                                                        <i class="fas fa-flag"></i> في القائمة السوداء
                                                    </span>
                                                @else
                                                    <span class="text-success">-</span>
                                                @endif
                                            </td>
                                            <td class="no-print align-middle text-center">
                                                @if ($employee['account_status'] === 'pending')
                                                    <div class="d-flex gap-1 justify-content-center">
                                                        <button type="button" class="btn btn-sm btn-success"
                                                                onclick="reviewSelfSubmission({{ $employee['id'] }}, 'accept')">
                                                            <i class="fas fa-check"></i> قبول
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="reviewSelfSubmission({{ $employee['id'] }}, 'reject')">
                                                            <i class="fas fa-times"></i> رفض
                                                        </button>
                                                    </div>
                                                @elseif ($employee['account_status'] === 'rejected')
                                                    <span class="badge bg-secondary">مرفوض</span>
                                                @else
                                                    <span class="text-secondary">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-12">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 h-16 w-16 text-gray-400" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.414.414l5.586 5.586A1 1 0 0120 9.586V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-gray-500">لا توجد طلبات تسجيل ذاتي بانتظار المراجعة</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Self Registration Link Modal -->
    <div class="modal fade" id="selfRegistrationLinkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content" x-data="selfRegLinkModal()">
                <div class="reg-link-header">
                    <h5>🔗 رابط تسجيل الموظفين الذاتي</h5>
                    <p>اختر المشروع والدور الوظيفي، ثم شارك الرابط مع الموظفين المعنيين ليعبّوا بياناتهم بنفسهم.</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="reg-link-body">
                    <div class="project-select-wrap">
                        <label>المشروع</label>
                        <select class="form-select" x-model="projectId">
                            @foreach ($projects as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="role-card pm-link" x-show="projectsMissingManager[projectId]">
                        <div class="role-card-head">
                            <span class="role-chip project_manager">
                                <span>🏢</span>
                                <span>مدير هذا المشروع</span>
                            </span>
                        </div>
                        <p class="text-muted mb-2" style="font-size: 0.78rem;">
                            هذا المشروع لا يوجد له مدير بعد. شارك هذا الرابط مع الشخص المعني — سيُعيَّن تلقائيًا
                            كمديرٍ لهذا المشروع دون الحاجة لكتابة اسمه يدويًا.
                        </p>
                        <div class="role-link-row">
                            <input type="text" readonly class="role-link-input"
                                   :id="'self-reg-link-pm-' + projectId"
                                   :value="'{{ url('/register-employee/project-manager') }}/' + projectId">
                            <button type="button" class="copy-btn" :class="{ copied: copiedRole === 'pm-' + projectId }"
                                    x-on:click="copyProjectManagerLink()">
                                <span x-show="copiedRole !== 'pm-' + projectId">📋 نسخ</span>
                                <span x-show="copiedRole === 'pm-' + projectId">✅ تم النسخ</span>
                            </button>
                        </div>
                    </div>

                    <template x-for="(info, role) in roles" :key="role">
                        <div class="role-card" x-show="isRoleAllowed(role)">
                            <div class="role-card-head">
                                <span class="role-chip" :class="role">
                                    <span x-text="info.icon"></span>
                                    <span x-text="info.label"></span>
                                </span>
                            </div>
                            <div class="role-link-row">
                                <input type="text" readonly class="role-link-input"
                                       :id="'self-reg-link-' + role"
                                       :value="'{{ url('/register-employee') }}/' + projectId + '/' + role">
                                <button type="button" class="copy-btn" :class="{ copied: copiedRole === role }"
                                        x-on:click="copyLink(role)">
                                    <span x-show="copiedRole !== role">📋 نسخ</span>
                                    <span x-show="copiedRole === role">✅ تم النسخ</span>
                                </button>
                            </div>
                        </div>
                    </template>

                    @if (in_array(Auth::user()->role, ['admin', 'hr_manager', 'hr_assistant']))
                        <div class="divider-label">رابط خاص بقسم الموارد البشرية</div>
                        <div class="role-card pm-link">
                            <div class="role-card-head">
                                <span class="role-chip project_manager">
                                    <span>🏢</span>
                                    <span>مدير مشروع جديد</span>
                                </span>
                            </div>
                            <p class="text-muted mb-2" style="font-size: 0.78rem;">
                                رابط مستقل لا يتبع مشروعًا محددًا — المتقدم يدخل اسم المشروع الجديد الذي سيديره بنفسه.
                            </p>
                            <div class="role-link-row">
                                <input type="text" readonly class="role-link-input"
                                       id="self-reg-link-project_manager"
                                       value="{{ url('/register-employee/project-manager') }}">
                                <button type="button" class="copy-btn" :class="{ copied: copiedRole === 'project_manager' }"
                                        x-on:click="copyLink('project_manager')">
                                    <span x-show="copiedRole !== 'project_manager'">📋 نسخ</span>
                                    <span x-show="copiedRole === 'project_manager'">✅ تم النسخ</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="divider-label">رابط تحديث البيانات البنكية</div>
                    <div class="role-card">
                        <p class="text-muted mb-2" style="font-size: 0.78rem;">
                            اختر الموظف لإنشاء رابط خاص به لتعديل بياناته البنكية.
                        </p>
                        <select class="form-select mb-2" x-model="bankLinkEmployeeId">
                            <option value="">اختر الموظف...</option>
                            <template x-for="emp in (employeesByProject[projectId] || [])" :key="emp.id">
                                <option :value="emp.id" x-text="emp.name"></option>
                            </template>
                        </select>
                        <div class="role-link-row" x-show="bankLinkEmployeeId">
                            <input type="text" readonly class="role-link-input" id="bank-update-link"
                                   :value="'{{ url('/bank-update-request') }}/' + bankLinkEmployeeId">
                            <button type="button" class="copy-btn" :class="{ copied: copiedRole === 'bank_update' }"
                                    x-on:click="copyBankLink()">
                                <span x-show="copiedRole !== 'bank_update'">📋 نسخ</span>
                                <span x-show="copiedRole === 'bank_update'">✅ تم النسخ</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Employee Modal -->
    <div class="modal fade" id="createEmployeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة موظف جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createEmployeeForm" action="{{ route('employees.store') }}" method="POST"
                      enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @include('Employees.employee-form-fields')
                    <div class="modal-footer bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            إضافة الموظف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('selfRegLinkModal', () => ({
                    projectId: '{{ array_key_first($projects) }}',
                    copiedRole: null,
                    roles: {
                        shelf_stacker: { label: 'مصفف أرفف', icon: '📦' },
                        supervisor: { label: 'مشرف', icon: '🧑‍💼' },
                        area_manager: { label: 'مشرف المشرفين', icon: '🧑‍✈️' }
                    },
                    projectAllowedRoles: @json($projectAllowedRoles ?? []),
                    employeesByProject: @json($employeesForBankLink ?? []),
                    projectsMissingManager: @json($projectsObjects->mapWithKeys(fn ($p) => [$p->id => is_null($p->manager_id)])),
                    bankLinkEmployeeId: '',
                    isRoleAllowed(role) {
                        const allowed = this.projectAllowedRoles[this.projectId];
                        return !allowed || allowed.includes(role);
                    },
                    copyLink(role) {
                        const input = document.getElementById('self-reg-link-' + role);
                        input.select();
                        navigator.clipboard.writeText(input.value).then(() => {
                            this.copiedRole = role;
                            setTimeout(() => { if (this.copiedRole === role) this.copiedRole = null; }, 1800);
                        }).catch(() => document.execCommand('copy'));
                    },
                    copyProjectManagerLink() {
                        const role = 'pm-' + this.projectId;
                        const input = document.getElementById('self-reg-link-' + role);
                        input.select();
                        navigator.clipboard.writeText(input.value).then(() => {
                            this.copiedRole = role;
                            setTimeout(() => { if (this.copiedRole === role) this.copiedRole = null; }, 1800);
                        }).catch(() => document.execCommand('copy'));
                    },
                    copyBankLink() {
                        const input = document.getElementById('bank-update-link');
                        input.select();
                        navigator.clipboard.writeText(input.value).then(() => {
                            this.copiedRole = 'bank_update';
                            setTimeout(() => { if (this.copiedRole === 'bank_update') this.copiedRole = null; }, 1800);
                        }).catch(() => document.execCommand('copy'));
                    }
                }));
            });

            function reviewSelfSubmission(employeeId, action) {
                const confirmMsg = action === 'accept'
                    ? 'هل تريد قبول بيانات هذا الموظف وإضافته إلى المشروع؟'
                    : 'هل تريد رفض بيانات هذا الموظف؟';
                if (!confirm(confirmMsg)) return;

                fetch(`/employees/${employeeId}/review-submission`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ action: action }),
                })
                    .then(res => res.json())
                    .then(result => {
                        if (result.success) {
                            window.location.reload();
                        } else {
                            alert(result.message || 'حدث خطأ أثناء معالجة الطلب');
                        }
                    })
                    .catch(() => alert('حدث خطأ أثناء الاتصال بالخادم'));
            }
        </script>
    @endpush
@endsection

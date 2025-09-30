@extends('layouts.master')
@section('title', 'بروفايل الموظف - ' . $emp->name)
<style>
    :root {
        --primary: #6e48aa;
        --secondary: #9d50bb;
        --accent: #4776E6;
        --success: #09d421;
        --danger: #f72585;
        --warning: #ff9e00;
        --info: #00b4d8;
        --dark: #1e293b;
        --light: #f8fafc;
        --card-bg: rgba(255, 255, 255, 0.95);
    }

    .profile-container {
        font-family: 'Inter', -apple-system, sans-serif;
        line-height: 1.6;
        width: 100%;
        margin: 0 auto;
        padding: 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        min-height: 100vh;
    }

    /* Modern Header with Glass Effect */
    .employee-header {
        padding: 2rem;
        background: linear-gradient(to right, var(--primary), var(--secondary));
        color: white;
        position: relative;
        overflow: hidden;
    }

    .employee-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
        transform: rotate(30deg);
    }

    /* Profile Card */
    .profile-card {
        display: flex;
        background: var(--card-bg);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        overflow: hidden;
        max-width: 1350px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .profile-avatar {
        position: relative;
        padding: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(245, 247, 250, 0.8) 100%);
    }

    .avatar-image {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .avatar-image:hover {
        transform: scale(1.05);
    }

    .request-types-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        gap: 10px;
    }

    /* Request type link styles */
    .request-type-link {
        text-decoration: none;
        color: inherit;
        flex: 1;
    }

    /* Request type item styles */
    .request-type {
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: transform 0.2s ease;
    }

    .request-type-link:hover .request-type {
        transform: translateY(-3px);
    }

    .request-type-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .request-type-count {
        font-weight: bold;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .request-type-label {
        font-size: 12px;
        color: #666;
        text-align: center;
    }


    .status-indicator {
        position: absolute;
        bottom: 30px;
        right: 30px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .status-indicator.active {
        background: var(--success);
    }

    .status-indicator.inactive {
        background: var(--danger);
    }

    .profile-content {
        flex: 1;
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        align-items: right;
        text-align: center;
    }

    .profile-title {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .profile-title h1 {
        margin: 0;
        font-size: 2rem;
        color: var(--dark);
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .role-badge {
        background: linear-gradient(to right, var(--accent), #4776E6);
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 24px;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(71, 118, 230, 0.3);
    }

    .profile-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 17px;
        font-weight: 700;
    }

    .meta-item i {
        color: var(--accent);
        font-size: 1rem;
    }

    .profile-actions {
        display: flex;
        gap: 1rem;
        margin-top: auto;
    }

    .action-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .action-button.whatsapp {
        background: linear-gradient(to right, #25D366, #128C7E);
        color: white;
        box-shadow: 0 2px 10px rgba(37, 211, 102, 0.3);
    }

    .action-button.secondary {
        background: rgba(255, 255, 255, 0.9);
        color: var(--dark);
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .action-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    /* Statistics Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        padding: 40px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .stat-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        text-decoration: none;
        border: 1px solid rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .stat-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .stat-description {
        font-size: 0.9rem;
        color: #64748b;
    }

    /* Unified Sections */
    .unified-sections {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 28px;
        padding: 40px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .unified-section {
        background: var(--card-bg);
        padding: 28px 32px;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .unified-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
    }

    .section-header {
        margin-bottom: 20px;
        color: var(--primary);
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        padding-bottom: 12px;
    }

    .section-header h2 {
        margin: 0;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-body {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .info-row {
        display: flex;
        margin-bottom: 14px;
        align-items: center;
    }

    .info-label {
        font-weight: 600;
        width: 180px;
        color: var(--dark);
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        flex: 1;
        font-size: 0.95rem;
        font-weight: 600;
        color: #34373c;
    }

    /* Badges */
    .badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-success {
        background: rgba(9, 212, 33, 0.1);
        color: var(--success);
    }

    .badge-warning {
        background: rgba(255, 158, 0, 0.1);
        color: var(--warning);
    }

    .badge-danger {
        background: rgba(247, 37, 133, 0.1);
        color: var(--danger);
    }

    .badge-info {
        background: rgba(0, 180, 216, 0.1);
        color: var(--info);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }

    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 15px;
    }

    .empty-state h4 {
        color: #94a3b8;
        margin-bottom: 10px;
    }

    /* Phone Icon */
    .phone-icon {
        width: 24px;
        height: 24px;
        object-fit: contain;
        margin-left: 8px;
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .unified-section {
        animation: fadeIn 0.4s ease forwards;
    }

    .unified-section:nth-child(1) {
        animation-delay: 0.1s;
    }

    .unified-section:nth-child(2) {
        animation-delay: 0.2s;
    }

    .unified-section:nth-child(3) {
        animation-delay: 0.3s;
    }

    .unified-section:nth-child(4) {
        animation-delay: 0.4s;
    }

    .unified-section:nth-child(5) {
        animation-delay: 0.5s;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .profile-card {
            flex-direction: column;
        }

        .profile-avatar {
            padding: 2rem;
        }

        .profile-content {
            padding: 2rem;
        }

        .unified-sections {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
            padding: 20px;
        }

        .unified-sections {
            grid-template-columns: 1fr;
            padding: 20px;
        }

        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.3rem;
        }

        .info-label {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .employee-header {
            padding: 1.5rem;
        }

        .profile-card {
            margin: 0 -1rem;
            border-radius: 0;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .profile-title h1 {
            font-size: 1.6rem;
        }
    }
</style>

@section('content')
    <div class="profile-container" x-data="{ editRequestOpen: false, editModalOpen: false }">
        <div class="employee-header">
            <div class="profile-card">
                <div class="profile-avatar">
                    <div class="relative w-32 h-32">
                        <img src="{{ $emp->user->personal_image }}" alt="{{ $emp->name }}"
                            class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md" id="profileImage">

                        <div
                            class="bottom-0 right-2 w-4 h-4 rounded-full border-2 border-white
        {{ $emp->user->account_status === 'active' ? 'bg-green-500' : 'bg-gray-400' }}">
                        </div>
                        @php
                            $authUser = Auth::user();
                            $excludedRoles = ['admin', 'hr_manager', 'hr_assistant'];
                        @endphp

                        @if (request()->routeIs('employees.show') && $authUser->employee?->id === $emp?->user_id)
                            @php
                                $isExcludedRole = in_array($authUser->role, $excludedRoles);
                                $ownsEmployee = $authUser->employee && $authUser->employee->id === $emp->id;
                            @endphp

                            @if (!$isExcludedRole && $ownsEmployee)
                                <button type="button"
                                    class="absolute top-0 right-0 bg-black text-white rounded-full p-1.5 shadow-lg hover:bg-blue-600 transition-all duration-200"
                                    onclick="document.getElementById('profilePhotoInput').click()" id="editButton">
                                    <i class="bi bi-pencil-fill text-xs"></i>
                                </button>
                            @endif
                        @endif

                    </div>

                </div>

                <form id="avatarUploadForm" action="{{ route('employees.updatePhoto', $emp) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="profilePhotoInput" name="profile_photo_path"
                        accept="image/jpeg,image/png,image/gif" style="display: none;">
                </form>
                <div class="profile-content">
                    <div class="profile-title">
                        <h1>{{ $emp->user->name }}</h1>
                        <div class="profile-title flex items-center gap-3">
                            <span class="role-badge">{{ __($emp->user->role) }}</span>

                            @if ($canEdit)
                                <button @click="editModalOpen = true" class="text-warning hover:text-orange-600"
                                    title="تعديل بيانات الموظف">
                                    <i class="fas fa-pen-to-square"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="profile-meta">
                        <div class="meta-item">
                            <i class="fas fa-id-card"></i>
                            <span>ID: {{ $emp->id }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-briefcase"></i>
                            <span>{{ $emp->job }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="profile-actions">
                        <a href="{{ $emp->user->generateWhatsappLink($emp->user->contact_info['phone_number']) }}"
                            class="action-button whatsapp" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                            <span>Contact</span>
                        </a>
                        <a href="mailto:{{ $emp->user->email }}" class="action-button secondary">
                            <i class="fas fa-envelope"></i>
                            <span>Email</span>
                        </a>
                        @if(\Illuminate\Support\Facades\Auth::user()->role !== 'admin')
                        <button @click="editRequestOpen = true" class="action-button"
                            style="background: linear-gradient(to right, var(--warning), #ff9500); color: white; box-shadow: 0 2px 10px rgba(255, 158, 0, 0.3);">
                            <i class="fas fa-edit"></i>
                            <span>إرسال طلب تعديل</span>
                        </button>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->role == 'admin')
                            <div class="mt-2">
                                <a href="{{ url('/admin/impersonate/' . $emp->user->id) }}"
                                   class="btn btn-warning">
                                    <i class="bi bi-person-check-fill"></i> الدخول كـ {{ $emp->user->name }}
                                </a>
                            </div>
                        @endif
                        @if ($canReplace)
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#replaceEmployeeModal">
                                <i class="fas fa-exchange-alt"></i> استبدال موظف
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Replace Employee Modal -->
        <div class="modal fade" id="replaceEmployeeModal" tabindex="-1" aria-labelledby="replaceEmployeeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="replaceEmployeeForm" action="{{ route('employees.replace') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="replaceEmployeeModalLabel">استبدال موظف</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <input type="text" name="old_employee_id" class="hidden" value="{{ $emp->id }}">
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    سبب طلب الاستبدال
                                </label>
                                <select name="replacement_reason" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 rtl text-right mb-3"
                                    id="replacement-reason-select">
                                    <option value="" selected disabled>اختر سبب الاستبدال
                                    </option>
                                    <option value="إنهاء خدمة">إنهاء خدمة</option>
                                    <option value="استقالة">استقالة</option>
                                    <option value="نقل داخلي">نقل داخلي</option>
                                    <option value="أداء ضعيف">أداء ضعيف</option>
                                    <option value="عدم التزام">عدم التزام</option>
                                    <option value="آخر">آخر (يرجى التحديد أدناه)</option>
                                </select>

                                <div id="other-reason-container" class="hidden mt-3">
                                    <label class="block mb-2 font-semibold text-gray-700">
                                        سبب آخر
                                    </label>
                                    <input type="text" name="other_reason"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 rtl text-right"
                                        placeholder="حدد السبب...">
                                </div>
                                @error('replacement_reason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ آخر دوام للموظف
                                    القديم</label>
                                <input type="text" id="last_working_date" name="last_working_date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('last_working_date') border-red-500 @enderror"
                                    placeholder="اختر تاريخ آخر دوام للموظف القديم" required>
                                @error('last_working_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <hr class="my-4">

                            @include('Employees.employee-form-fields')
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-success">استبدال الموظف</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Main Content -->
        <div class="profile-main-content">

            <div x-show="editRequestOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
                style="backdrop-filter: blur(5px);">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="editRequestOpen = false">
                        <div class="absolute inset-0 bg-gray-900 bg-opacity-75"></div>
                    </div>

                    <!-- Modal container -->
                    <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                        style="width: 90%; max-width: 600px;">
                        <div class="bg-white px-6 py-5">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                    <i class="fas fa-edit text-indigo-600"></i>
                                    طلب تعديل بيانات الموظف
                                </h3>
                                <button @click="editRequestOpen = false" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>

                            <form action="{{ route('employee-request.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $emp->id }}">

                                <!-- Field Selection -->
                                <div class="mb-4">
                                    <label for="edited_field" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-tag mr-1 text-indigo-500"></i>
                                        اختر الحقل المراد تعديله
                                    </label>
                                    <select name="edited_field" id="edited_field" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                                        <option value=""> اختر الحقل </option>
                                        @foreach ($editedFields as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Description -->
                                <div class="mb-6">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-align-left mr-1 text-indigo-500"></i>
                                        وصف التعديل المطلوب
                                    </label>
                                    <textarea name="description" id="description" rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                        placeholder="وضح التعديل المطلوب بشكل واضح..." required></textarea>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                    <button type="button" @click="editRequestOpen = false"
                                        class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
                                        إلغاء
                                    </button>
                                    <button type="submit"
                                        class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 font-medium shadow-md hover:shadow-indigo-200">
                                        <i class="fas fa-paper-plane mr-1"></i>
                                        إرسال الطلب
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Unified Sections -->
            <div class="unified-sections">
                <!-- Employment Data Section -->
                <div class="unified-section">
                    <div class="section-header">
                        <h2><i class="bi bi-briefcase-fill"></i>بيانات الوظيفة</h2>
                    </div>
                    <div class="section-body">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-calendar-event-fill" style="color: var(--info)"></i>تاريخ الانضمام:
                            </div>
                            <div class="info-value">
                                {{ \Carbon\Carbon::parse($emp->joining_date)->locale('ar')->translatedFormat('d F Y') }}
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-hourglass-split" style="color: var(--warning)"></i>مدة العمل:
                            </div>
                            <div class="info-value">
                                {{ $emp->getWorkDuration($emp->joining_date) }}
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-geo-alt-fill" style="color: var(--danger)"></i>منطقة العمل:
                            </div>
                            <div class="info-value">
                                {{ $emp->user->contact_info['area'] }}
                            </div>
                        </div>

                        @if ($emp->stop_reason)
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="bi bi-slash-circle-fill" style="color: var(--danger)"></i>سبب التوقف:
                                </div>
                                <div class="info-value" style="color: var(--danger); font-weight: 600;">
                                    {{ $emp->stop_reason }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Personal Data Section -->
                <div class="unified-section">
                    <div class="section-header">
                        <h2><i class="bi bi-person-vcard-fill"></i>البيانات الشخصية</h2>
                    </div>
                    <div class="section-body">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-gender-ambiguous" style="color: var(--secondary)"></i>الجنس:
                            </div>
                            <div class="info-value">{{ $emp->user->getGender() }}</div>
                        </div>
                        @php
                            $baseNationality = preg_replace('/(ة|ه)$/u', '', $emp->user->nationality);
                            $flagCode = null;

                            if (isset($nationalityFlags[$emp->user->nationality])) {
                                $flagCode = $nationalityFlags[$emp->user->nationality];
                            } elseif (isset($nationalityFlags[$baseNationality])) {
                                $flagCode = $nationalityFlags[$baseNationality];
                            }
                        @endphp
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-flag-fill" style="color: var(--success)"></i>الجنسية:
                            </div>
                            <div class="info-value d-flex align-items-center gap-2">
                                @if ($emp->user->nationality)
                                    @if ($flagCode)
                                        <img src="https://flagcdn.com/24x18/{{ $flagCode }}.png"
                                            alt="{{ $emp->user->nationality }}"
                                            style="width: 20px; height: 15px; object-fit: cover;">
                                    @endif
                                    <span>{{ $emp->user->nationality }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-calendar-event-fill" style="color: var(--info)"></i>تاريخ الميلاد:
                            </div>
                            <div class="info-value" dir="rtl">
                                {{ \Carbon\Carbon::parse($emp->user->birthday)->locale('ar')->translatedFormat('d F Y') }}
                                —
                                {{ $emp->user->getAge() }} عامًا
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-card-heading" style="color: var(--dark)"></i>رقم الهوية:
                            </div>
                            <div class="info-value">{{ $emp->user->id_card }}</div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-shield-check" style="color: var(--success)"></i>الشهادة الصحية:
                            </div>
                            <div class="info-value">
                                <span
                                    style="color: {{ $emp->health_card ? 'var(--success)' : 'var(--danger)' }}; font-weight: 600;">
                                    {{ $emp->health_card ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="unified-section">
                    <div class="section-header">
                        <h2><i class="bi bi-telephone-fill"></i>بيانات التواصل</h2>
                    </div>
                    <div class="section-body">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-phone" style="color: var(--secondary)"></i>نوع الجوال:
                            </div>
                            <div class="info-value d-flex align-items-center gap-2">
                                {{ $emp->user->contact_info['phone_type'] }}
                                @if (strtolower($emp->user->contact_info['phone_type']) === 'android')
                                    <img src="{{ asset('build/assets/img/android.png') }}" alt="Android"
                                        class="phone-icon">
                                @elseif(strtolower($emp->user->contact_info['phone_type']) === 'iphone')
                                    <img src="{{ asset('build/assets/img/iphone.png') }}" alt="iPhone"
                                        class="phone-icon">
                                @else
                                    <img src="/images/icons/phone.png" alt="Phone" class="phone-icon">
                                @endif
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-telephone-inbound-fill" style="color: var(--success)"></i>رقم الجوال:
                            </div>
                            <div class="info-value">
                                @if ($emp->user->contact_info['phone_number'])
                                    <a href="{{ $emp->user->generateWhatsappLink($emp->user->contact_info['phone_number']) }}"
                                        target="_blank" style="color: #25D366;" title="Chat on WhatsApp">
                                        <i class="fab fa-whatsapp" style="font-size: 18px;"></i>
                                    </a>
                                @endif
                                {{ $emp->user->contact_info['phone_number'] }}
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-house-door-fill" style="color: var(--info)"></i>مقر الإقامة:
                            </div>
                            <div class="info-value">{{ $emp->user->contact_info['residence'] }}</div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-geo-fill" style="color: var(--danger)"></i>الحي السكني:
                            </div>
                            <div class="info-value">{{ $emp->user->contact_info['residence_neighborhood'] }}</div>
                        </div>
                    </div>
                </div>

                <!-- Uniform Sizes Section -->
                <div class="unified-section">
                    <div class="section-header">
                        <h2><i class="fas fa-tshirt"></i>مقاسات الملابس</h2>
                    </div>
                    <div class="section-body">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-tshirt" style="color: var(--info)"></i>مقاس التي شيرت:
                            </div>
                            <div class="info-value">{{ $emp->user->size_info['Tshirt_size'] }}</div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-pants" style="color: var(--secondary)"></i>مقاس البنطال:
                            </div>
                            <div class="info-value">{{ $emp->user->size_info['pants_size'] }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-shoe-prints" style="color: black"></i>مقاس الحذاء:
                            </div>
                            <div class="info-value">{{ $emp->user->size_info['Shoes_size'] ?? '-' }}</div>
                        </div>
                    </div>
                </div>
                <!-- Financial Data Section -->
                <div class="unified-section">
                    <div class="section-header">
                        <h2><i class="bi bi-bank2"></i>البيانات المالية</h2>
                    </div>
                    <div class="section-body">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-building" style="color: var(--primary)"></i>اسم البنك:
                            </div>
                            <div class="info-value">
                                @if($emp->bank_name)
                                    <span>{{ $emp->bank_name }}</span>
                                @else
                                    <span style="color: #64748b;">غير متوفر</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-credit-card" style="color: var(--secondary)"></i>رقم الآيبان:
                            </div>
                            <div class="info-value">
                                @if($emp->iban)
                                    <span style="font-family: 'Courier New', monospace; font-weight: 600; color: var(--dark);">
                        {{ $emp->iban }}
                    </span>
                                @else
                                    <span style="color: #64748b;">غير متوفر</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-person-badge" style="color: var(--success)"></i>اسم صاحب الحساب:
                            </div>
                            <div class="info-value">
                                @if($emp->owner_account_name)
                                    <span style="font-weight: 600; color: var(--dark);">
                        {{ $emp->owner_account_name }}
                    </span>
                                @else
                                    <span style="color: #64748b;">غير متوفر</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Vehicle Information Section -->
                @if ($emp->vehicle_info['vehicle_type'] || $emp->vehicle_info['vehicle_model'] || $emp->vehicle_info['vehicle_ID'])
                    <div class="unified-section">
                        <div class="section-header">
                            <h2><i class="bi bi-truck-front-fill"></i>بيانات المركبة</h2>
                        </div>
                        <div class="section-body">
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="bi bi-truck" style="color: var(--primary)"></i>نوع المركبة:
                                </div>
                                <div class="info-value">{{ $emp->vehicle_info['vehicle_type'] }}</div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="bi bi-tools" style="color: var(--secondary)"></i>موديل المركبة:
                                </div>
                                <div class="info-value">{{ $emp->vehicle_info['vehicle_model'] }}</div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="bi bi-credit-card-2-front" style="color: var(--info)"></i>رقم لوحة المركبة:
                                </div>
                                <div class="info-value">{{ $emp->vehicle_info['vehicle_ID'] }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Project Data Section -->
                <div class="unified-section">
                    <div class="section-header">
                        <h2><i class="bi bi-person-vcard-fill me-1 text-primary"></i>بيانات المشروع</h2>
                    </div>
                    <div class="section-body">
                        @if ($emp->project)
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="bi bi-briefcase text-secondary me-1"></i>اسم المشروع:
                                </div>
                                <div class="info-value">
                                    {{ $emp->project->name ?? 'غير متوفر' }}
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="bi bi-file-earmark-text text-secondary me-1"></i>وصف المشروع:
                                </div>
                                <div class="info-value">
                                    {{ $emp->project->description ?? 'غير متوفر' }}
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="bi bi-person-circle text-secondary me-1"></i>مدير المشروع:
                                </div>
                                <div class="info-value">
                                    {{ $emp->project->manager?->name ?? 'غير متوفر' }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-3 text-muted fw-bold">
                                <i class="bi bi-exclamation-circle-fill me-1 text-danger"></i>
                                لم يتم تعيين الموظف في أي مشروع بعد
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <a href="{{ route('employees.replacements', $emp->id) }}" class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(157, 80, 187, 0.1); color: var(--secondary);">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stat-value">{{ $emp->replacements->count() }}</div>
                    <div class="stat-title">الاستبدالات</div>
                    <div class="stat-description">عرض سجل الاستبدالات</div>
                </a>

                <a href="{{ route('employees.alerts', $emp->id) }}" class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(255, 158, 0, 0.1); color: var(--warning);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-value">{{ $emp->alerts->count() }}</div>
                    <div class="stat-title">التنبيهات</div>
                    <div class="stat-description">عرض سجل التنبيهات</div>
                </a>

                <a href="{{ route('employees.deductions', $emp->id) }}" class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(247, 37, 133, 0.1); color: var(--danger);">
                        <i class="fas fa-minus-circle"></i>
                    </div>
                    <div class="stat-value">{{ $emp->deductions->count() }}</div>
                    <div class="stat-title">الخصومات</div>
                    <div class="stat-description">عرض سجل الخصومات</div>
                </a>

                <a href="{{ route('employees.advances', $emp->id) }}" class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(0, 180, 216, 0.1); color: var(--info);">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="stat-value">{{ $emp->advances->count() }}</div>
                    <div class="stat-title">السلف</div>
                    <div class="stat-description">عرض سجل السلف</div>
                </a>

                <a href="{{ route('employees.increases', $emp->id) }}" class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(9, 212, 33, 0.1); color: var(--success);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-value">{{ $emp->increases->count() }}</div>
                    <div class="stat-title">الزيادات</div>
                    <div class="stat-description">عرض سجل الزيادات</div>
                </a>

                <a href="{{ route('employees.assignments', $emp->id) }}" class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(110, 72, 170, 0.1); color: var(--primary);">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-value">{{ $emp->temporaryAssignments->count() }}</div>
                    <div class="stat-title">المهام المؤقتة</div>
                    <div class="stat-description">عرض المهام المؤقتة</div>
                </a>
                <div class="stat-card">
                    <a href="{{ route('employee-request.index', ['search' => $emp->name]) }}"
                        class="stat-card-main-link">
                        <div class="stat-icon" style="background-color: rgba(110, 72, 170, 0.1); color: var(--primary);">
                            <i class="fas fa-tasks"></i>
                        </div>

                        <div class="stat-value">{{ $emp->employeeRequests->count() }}</div>
                        <div class="stat-title">طلبات الموظف</div>
                    </a>

                    <div class="request-types-container">
                        <!-- Tool Bag Request -->
                        <a href="{{ route('employee-request.index', ['search' => $emp->name, 'request_type_id' => 8]) }}"
                            class="request-type-link">
                            <div class="request-type">
                                <span class="request-type-icon"
                                    style="background-color: rgba(75, 192, 192, 0.1); color: #4bc0c0;">
                                    <i class="fas fa-briefcase"></i>
                                </span>
                                <span class="request-type-count">{{ $tool_bag_count }}</span>
                                <span class="request-type-label">حقيبة أدوات</span>
                            </div>
                        </a>

                        <!-- United Clothes Request -->
                        <a href="{{ route('employee-request.index', ['search' => $emp->name, 'request_type_id' => 7]) }}"
                            class="request-type-link">
                            <div class="request-type">
                                <span class="request-type-icon"
                                    style="background-color: rgba(255, 159, 64, 0.1); color: #ff9f40;">
                                    <i class="fas fa-tshirt"></i>
                                </span>
                                <span class="request-type-count">{{ $united_clothes_count }}</span>
                                <span class="request-type-label">ملابس موحدة</span>
                            </div>
                        </a>

                        <!-- Health Card Request -->
                        <a href="{{ route('employee-request.index', ['search' => $emp->name, 'request_type_id' => 4]) }}"
                            class="request-type-link">
                            <div class="request-type">
                                <span class="request-type-icon"
                                    style="background-color: rgba(54, 162, 235, 0.1); color: #36a2eb;">
                                    <i class="fas fa-heartbeat"></i>
                                </span>
                                <span class="request-type-count">{{ $generate_health_card_count }}</span>
                                <span class="request-type-label">بطاقة صحية</span>
                            </div>
                        </a>
                    </div>

                    <a href="{{ route('employee-request.index', ['search' => $emp->name]) }}"
                        class="stat-description-link">
                        <div class="stat-description">عرض جميع الطلبات</div>
                    </a>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(71, 118, 230, 0.1); color: var(--accent);">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-value">
                        @php
                            $totalIncreases = $emp->increases->sum('amount');
                            $totalDeductions = $emp->deductions->sum('amount');
                            $netAdjustment = $totalIncreases - $totalDeductions;
                        @endphp
                        {{ number_format($netAdjustment, 2) }} ر.س
                    </div>
                    <div class="stat-title">إجمالي التعديلات</div>
                    <div class="stat-description">صافي الزيادات والخصومات</div>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                flatpickr("#edit_birthday", {
                    locale: "ar",
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "F j, Y",
                    allowInput: true,
                    defaultHour: 12,
                });

                flatpickr("#edit_joining_date", {
                    locale: "ar",
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "F j, Y",
                    allowInput: true,
                    defaultHour: 12,
                });

                flatpickr("#last_working_date", {
                    locale: "ar",
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "F j, Y",
                    allowInput: true,
                    defaultHour: 12,
                });
                flatpickr("#joining_date", {
                    locale: "ar",
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "F j, Y",
                    allowInput: true,
                    defaultHour: 12,
                });
                flatpickr("#birthday", {
                    locale: "ar",
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "F j, Y",
                    allowInput: true,
                    defaultHour: 12,
                });

            });
        </script>


        <script>
            document.getElementById('updateEmployeeForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم بنجاح',
                            text: result.message,
                            confirmButtonText: 'حسنًا'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        // handle Laravel validation errors
                        let errorMessages = '';

                        if (result.errors) {
                            errorMessages = Object.values(result.errors)
                                .flat()
                                .join('\n');
                        } else {
                            errorMessages = result.message || 'حدث خطأ غير متوقع';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'فشل الحفظ',
                            text: errorMessages,
                            confirmButtonText: 'حسنًا'
                        });
                    }

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ في الاتصال',
                        text: error.message || 'تعذر الاتصال بالخادم',
                        confirmButtonText: 'حسنًا'
                    });
                }
            });
        </script>
        <script>
            document.getElementById('replaceEmployeeForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);
                const submitButton = form.querySelector('button[type="submit"]');

                // Show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري المعالجة...';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        // Success notification
                        Swal.fire({
                            icon: 'success',
                            title: 'تم بنجاح!',
                            html: `
                    <div class="text-right">
                        <p>${result.message}</p>
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <p class="font-semibold">تفاصيل الاستبدال:</p>
                            <p>الموظف القديم: ${result.data.old_employee.name}</p>
                            <p>الموظف الجديد: ${result.data.new_employee.name}</p>
                            <p>تاريخ الاستبدال: ${new Date(result.data.replacement.replacement_date).toLocaleDateString('ar-EG')}</p>
                        </div>
                    </div>
                `,
                            confirmButtonText: 'حسنًا',
                            customClass: {
                                confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md'
                            }
                        }).then(() => {
                            // Close modal and refresh page
                            $('#replaceEmployeeModal').modal('hide');
                            window.location.reload();
                        });
                    } else {
                        // Error handling
                        let errorMessage = result.message || 'حدث خطأ غير متوقع';

                        if (result.errors) {
                            errorMessage = Object.values(result.errors).flat().join('<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            html: `<div class="text-right">${errorMessage}</div>`,
                            confirmButtonText: 'حسنًا',
                            customClass: {
                                confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-md'
                            }
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ في الاتصال',
                        text: 'تعذر الاتصال بالخادم',
                        confirmButtonText: 'حسنًا'
                    });
                } finally {
                    // Reset button state
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'استبدال الموظف';
                }
            }); <
            script >
                const supervisors = @json($supervisors);

            const projectSelect = document.getElementById('project-select');
            const supervisorSelect = document.getElementById('supervisor-select');

            if (projectSelect && supervisorSelect) {
                projectSelect.addEventListener('change', function() {
                    const selectedProjectId = this.value;
                    const filteredSupervisors = supervisors.filter(s => s.project_id == selectedProjectId);

                    supervisorSelect.innerHTML = '';

                    if (filteredSupervisors.length > 0) {
                        supervisorSelect.disabled = false;
                        supervisorSelect.innerHTML = `<option value="" disabled selected>اختر المشرف</option>`;

                        filteredSupervisors.forEach(s => {
                            const option = document.createElement('option');
                            option.value = s.id;
                            option.textContent = s.name;
                            supervisorSelect.appendChild(option);
                        });
                    } else {
                        supervisorSelect.disabled = true;
                        supervisorSelect.innerHTML =
                            `<option value="" disabled selected>لا يوجد مشرفين للمشروع المحدد</option>`;
                    }
                });

                if (projectSelect.value) {
                    projectSelect.dispatchEvent(new Event('change'));
                    @if (old('supervisor') || $emp->supervisor_id)
                        supervisorSelect.value = "{{ old('supervisor', $emp->supervisor_id) }}";
                    @endif
                }
            }
        </script>
        <script>
            document.getElementById('profilePhotoInput').addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    const maxSize = 2048 * 1024; // 2MB
                    const editBtn = document.getElementById('editButton');

                    if (!validTypes.includes(file.type)) {
                        alert('الرجاء اختيار صورة بصيغة JPEG أو PNG أو GIF');
                        return;
                    }

                    if (file.size > maxSize) {
                        alert('حجم الملف يجب أن لا يتجاوز 2MB');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('profileImage').src = e.target.result;
                    }
                    reader.readAsDataURL(file);

                    editBtn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin"></i>';
                    editBtn.disabled = true;
                    editBtn.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                    editBtn.classList.add('bg-gray-400');

                    document.getElementById('avatarUploadForm').submit();
                }
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const reasonSelect = document.getElementById('replacement-reason-select');
                const otherReasonContainer = document.getElementById('other-reason-container');

                reasonSelect.addEventListener('change', function() {
                    if (this.value === 'آخر') {
                        otherReasonContainer.classList.remove('hidden');
                        otherReasonContainer.querySelector('input').setAttribute('required', 'true');
                    } else {
                        otherReasonContainer.classList.add('hidden');
                        otherReasonContainer.querySelector('input').removeAttribute('required');
                    }
                });
            });
        </script>
    @endpush

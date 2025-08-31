<div class="modal-body p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Column 1 -->
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الاسم
                    الكامل</label>
                <input type="text" name="name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                    required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">اسم صاحب الحساب</label>
                <input type="text" name="owner_account_name" value="{{ old('owner_account_name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('owner_account_name') border-red-500 @enderror">
                @error('owner_account_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">اسم البنك</label>
                <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('owner_account_name') border-red-500 @enderror">
                @error('bank_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">المهنة في
                    الهوية</label>
                <input type="text" name="job"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('job') border-red-500 @enderror">
                @error('job')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم
                    الهوية</label>
                <input type="text" name="id_card"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('id_card') border-red-500 @enderror"
                    required>
                @error('id_card')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الجنسية</label>
                <input type="text" name="nationality"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nationality') border-red-500 @enderror"
                    required>
                @error('nationality')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ الميلاد</label>
                <input type="date" id="birthday" name="birthday"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('birthday') border-red-500 @enderror"
                    placeholder="ادخل تاريخ الميلاد" required onchange="calculateAge()">
                @error('birthday')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">العمر</label>
                <input type="number" id="age" name="age"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('age') border-red-500 @enderror"
                    readonly>
                @error('age')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الجنس</label>
                <select name="gender"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('gender') border-red-500 @enderror"
                    required>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                        ذكر</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                        أنثى</option>
                </select>
                @error('gender')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">مقر
                    الإقامة</label>
                <input type="text" name="residence"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('residence') border-red-500 @enderror"
                    required>
                @error('residence')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحي
                    السكني</label>
                <input type="text" name="residence_neighborhood"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('residence_neighborhood') border-red-500 @enderror"
                    required>
                @error('residence_neighborhood')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">نوع
                    المركبة</label>
                <input type="text" name="vehicle_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('vehicle_type') border-red-500 @enderror"
                    required>
                @error('vehicle_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">موديل
                    المركبة</label>
                <input type="text" name="vehicle_model"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('vehicle_model') border-red-500 @enderror"
                    required>
                @error('vehicle_model')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم لوحة
                    المركبة</label>
                <input type="text" name="vehicle_ID"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('vehicle_ID') border-red-500 @enderror"
                    required>
                @error('vehicle_ID')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>



            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">نوع
                    الشهادة</label>
                <select name="certificate_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('certificate_type') border-red-500 @enderror">
                    @foreach ($certificateTypes as $value => $label)
                        <option value="{{ $value }}" {{ old('certificate_type') == $value ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
                @error('certificate_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div x-data="{ maritalStatus: '{{ old('marital_status', 'single') }}' }">
                <!-- Marital Status Dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الحالة
                        الاجتماعية</label>
                    <select name="marital_status" x-model="maritalStatus"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('marital_status') border-red-500 @enderror">
                        @foreach ($maritalStatuses as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('marital_status') == $value ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                    @error('marital_status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Members Number (conditionally shown) -->
                <div x-show="maritalStatus !== 'single'" x-cloak style="padding-top:20px;">
                    <label class="block text-sm font-medium text-gray-700 mb-1">عدد أفراد
                        الأسرة</label>
                    <input type="number" name="members_number"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('members_number') border-red-500 @enderror"
                        min="0">
                    @error('members_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        </div>

        <!-- Column 2 -->
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">البريد
                    الإلكتروني</label>
                <input type="email" name="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                    required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم الآيبان</label>
                <input type="text" name="iban" value="{{ old('iban') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('iban') border-red-500 @enderror">
                @error('iban')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم
                    الجوال</label>
                <input type="tel" name="phone_number"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone_number') border-red-500 @enderror"
                    required>
                @error('phone_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">نوع
                    الجوال</label>
                <select name="phone_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone_type') border-red-500 @enderror"
                    required>
                    <option value="android" {{ old('phone_type') == 'android' ? 'selected' : '' }}>
                        أندرويد</option>
                    <option value="iphone" {{ old('phone_type') == 'iphone' ? 'selected' : '' }}>
                        آيفون</option>
                </select>
                @error('phone_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @php
                if ($authRole === 'project_manager') {
                    $filteredRoleLabels = $allowedForProjectManager;
                } elseif (in_array($authRole, ['hr_manager', 'hr_assistant'])) {
                    $filteredRoleLabels = $allowedForHrManager;
                } else {
                    $filteredRoleLabels = $roleLabels;
                }
            @endphp
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الدور الوظيفي</label>
                <select name="role" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror">
                    <option value="" disabled selected>اختر الدور الوظيفي</option>
                    @foreach ($filteredRoleLabels as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach

                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @if (($role && $role->hasPermissionTo('change_employees_password')) || Auth::user()->role === 'admin')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                        placeholder="أدخل كلمة المرور">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif



            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">منطقة
                    العمل</label>
                <input type="text" name="work_area"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('work_area') border-red-500 @enderror"
                    required>
                @error('work_area')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ
                    الإنضمام</label>
                <input type="text" id="joining_date" name="joining_date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('joining_date') border-red-500 @enderror"
                    placeholder="اختر تاريخ الانضمام" required>
                @error('joining_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">مقاس التي
                    شيرت</label>
                <select name="Tshirt_size"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('Tshirt_size') border-red-500 @enderror">
                    @foreach ($shirtSizes as $value => $label)
                        <option value="{{ $value }}" {{ old('Tshirt_size') == $value ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
                @error('Tshirt_size')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">مقاس
                    البنطال</label>
                <select name="pants_size"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('pants_size') border-red-500 @enderror">
                    @foreach ($pantsSizes as $value => $label)
                        <option value="{{ $value }}" {{ old('pants_size') == $value ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
                @error('pants_size')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">مقاس الحذاء
                </label>
                <select name="Shoes_size"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('Shoes_size') border-red-500 @enderror">
                    @foreach ($shoesSizes as $value => $label)
                        <option value="{{ $value }}" {{ old('Shoes_size') == $value ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
                @error('Shoes_size')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">هل لدى
                    الموظف شهادة صحية (كرت البلدية)؟</label>
                <select name="health_card"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('health_card') border-red-500 @enderror"
                    required>
                    <option value="" {{ old('health_card') == '' ? 'selected' : '' }}>اختر
                        الحالة</option>
                    <option value="1" {{ old('health_card') == '1' ? 'selected' : '' }}>
                        نعم</option>
                    <option value="0" {{ old('health_card') == '0' ? 'selected' : '' }}>لا
                    </option>
                </select>
                @error('health_card')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">المشروع</label>
                <select id="project-select" name="project"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('project') border-red-500 @enderror">
                    <option value="" disabled selected>اختر المشروع</option>
                    @foreach ($projects as $id => $name)
                        <option value="{{ $id }}" {{ old('project') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('project')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div id="supervisor-container">
                <label class="block text-sm font-medium text-gray-700 mb-1">المشرف</label>
                <select id="supervisor-select" name="supervisor"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('supervisor') border-red-500 @enderror"
                    disabled>
                    <option value="" disabled selected>اختر المشروع أولاً</option>
                </select>
                @error('supervisor')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div id="area-manager-container" style="display: none;">
                <label class="block text-sm font-medium text-gray-700 mb-1">مدير المنطقة</label>
                <select id="area-manager-select" name="area_manager"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('area_manager') border-red-500 @enderror"
                        disabled>
                    <option value="" disabled selected>اختر مدير المنطقة</option>
                    @foreach($area_managers as $id => $name)
                        <option value="{{ $id }}" {{ old('area_manager') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('area_manager')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الراتب</label>
                <input type="number" step="0.01" name="salary"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('salary') border-red-500 @enderror"
                    required>
                @error('salary')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">مستوى اللغة
                    الإنجليزية</label>
                <select name="english_level"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('english_level') border-red-500 @enderror">
                    @foreach ($englishLevels as $value => $label)
                        <option value="{{ $value }}" {{ old('english_level') == $value ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
                @error('english_level')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">صورة
                    الموظف</label>
                <div class="mt-1 flex items-center">
                    <input type="file" name="personal_image"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('personal_image') border-red-500 @enderror"
                        required>
                </div>
                @error('personal_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>
</div>
<script>
    const supervisors = @json($supervisors);
    const areaManagers = @json($area_managers);

    const projectSelect = document.getElementById('project-select');
    const supervisorSelect = document.getElementById('supervisor-select');
    const areaManagerSelect = document.getElementById('area-manager-select');
    const areaManagerContainer = document.getElementById('area-manager-container');
    const roleSelect = document.querySelector('select[name="role"]');

    // Function to toggle between supervisor and area manager based on role
    function toggleManagerSelection() {
        const selectedRole = roleSelect.value;
        const selectedProjectId = projectSelect.value;

        if (selectedRole === 'shelf_arranger') {
            // Show supervisor, hide area manager
            supervisorSelect.closest('div').style.display = 'block';
            areaManagerContainer.style.display = 'none';

            // Set required attributes
            supervisorSelect.setAttribute('required', 'required');
            areaManagerSelect.removeAttribute('required');

            // Update supervisors based on project
            updateSupervisors(selectedProjectId);

        } else if (selectedRole === 'supervisor') {
            // Show area manager, hide supervisor
            supervisorSelect.closest('div').style.display = 'none';
            areaManagerContainer.style.display = 'block';

            // Set required attributes
            supervisorSelect.removeAttribute('required');
            areaManagerSelect.setAttribute('required', 'required');

            // Update area managers based on project
            updateAreaManagers(selectedProjectId);

        } else {
            // For other roles, show supervisor and hide area manager
            supervisorSelect.closest('div').style.display = 'block';
            areaManagerContainer.style.display = 'none';

            // Set required attributes
            supervisorSelect.setAttribute('required', 'required');
            areaManagerSelect.removeAttribute('required');

            // Update supervisors based on project
            updateSupervisors(selectedProjectId);
        }
    }

    // Update supervisors based on project selection
    function updateSupervisors(projectId) {
        const filteredSupervisors = supervisors.filter(s => s.project_id == projectId);

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
            supervisorSelect.innerHTML = `<option value="" disabled selected>لا يوجد مشرفين للمشروع المحدد</option>`;
        }
    }

    // Update area managers based on project selection (same logic as supervisors)
    function updateAreaManagers(projectId) {
        // Check if areaManagers is an array of objects with project_id
        if (Array.isArray(areaManagers) && areaManagers.length > 0 && areaManagers[0].hasOwnProperty('project_id')) {
            const filteredAreaManagers = areaManagers.filter(am => am.project_id == projectId);

            areaManagerSelect.innerHTML = '';

            if (filteredAreaManagers.length > 0) {
                areaManagerSelect.disabled = false;
                areaManagerSelect.innerHTML = `<option value="" disabled selected>اختر مدير المنطقة</option>`;

                filteredAreaManagers.forEach(am => {
                    const option = document.createElement('option');
                    option.value = am.id;
                    option.textContent = am.name;
                    areaManagerSelect.appendChild(option);
                });
            } else {
                areaManagerSelect.disabled = true;
                areaManagerSelect.innerHTML = `<option value="" disabled selected>لا يوجد مديرين منطقة للمشروع المحدد</option>`;
            }
        } else {
            // If areaManagers doesn't have project_id or is in different format
            areaManagerSelect.disabled = false;
            areaManagerSelect.innerHTML = `<option value="" disabled selected>اختر مدير المنطقة</option>`;

            areaManagers.forEach(am => {
                const option = document.createElement('option');
                option.value = am.id;
                option.textContent = am.name;
                areaManagerSelect.appendChild(option);
            });
        }
    }

    // Update when project changes
    projectSelect.addEventListener('change', function() {
        const selectedProjectId = this.value;
        const selectedRole = roleSelect.value;

        if (selectedRole === 'supervisor') {
            updateAreaManagers(selectedProjectId);
        } else {
            updateSupervisors(selectedProjectId);
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial state based on role
        toggleManagerSelection();

        // If project is already selected, populate the appropriate select
        if (projectSelect.value) {
            const selectedRole = roleSelect.value;
            if (selectedRole === 'supervisor') {
                updateAreaManagers(projectSelect.value);
            } else {
                updateSupervisors(projectSelect.value);
            }

            @if (old('supervisor'))
                supervisorSelect.value = "{{ old('supervisor') }}";
            @endif
        }

        // Set area manager if exists
        @if (old('area_manager'))
            areaManagerSelect.value = "{{ old('area_manager') }}";
        @endif
    });

    // Listen for role changes
    roleSelect.addEventListener('change', toggleManagerSelection);
</script>
<script>
    const roleSelect = document.querySelector('select[name="role"]');
    const supervisorSelect = document.getElementById('supervisor-select');

    function toggleSupervisorRequired() {
        const selectedRole = roleSelect.value;

        if (selectedRole === 'supervisor') {
            supervisorSelect.removeAttribute('required');
        } else {
            supervisorSelect.setAttribute('required', 'required');
        }
    }

    roleSelect.addEventListener('change', toggleSupervisorRequired);

    document.addEventListener('DOMContentLoaded', toggleSupervisorRequired);
</script>
<script>
    function calculateAge() {
        const birthdayInput = document.getElementById('birthday');
        const ageInput = document.getElementById('age');

        if (birthdayInput.value) {
            const birthDate = new Date(birthdayInput.value);
            const today = new Date();

            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            ageInput.value = age;
        } else {
            ageInput.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('birthday').value) {
            calculateAge();
        }
    });
</script>

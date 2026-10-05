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

            <div x-data="{
                open: false,
                search: '',
                selected: '{{ old('nationality', $employee->user->nationality ?? '') }}',
                nationalities: ['سوريا','اليمن','مصر','السودان','باكستان','نيجيريا','الهند','الأردن','العراق','لبنان','فلسطين','بنغلاديش','المغرب','تونس','إثيوبيا','سعودي','إريتريا','الصومال','أفغانستان','إندونيسيا','الفلبين','سريلانكا','نيبال','كينيا','غانا','السنغال','أخرى'],
                get filtered() { return this.search ? this.nationalities.filter(n => n.includes(this.search)) : this.nationalities; }
            }" class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">الجنسية</label>
                <input type="hidden" name="nationality" :value="selected" required>
                <div @click="open=!open"
                     class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 cursor-pointer flex justify-between items-center @error('nationality') border-red-500 @enderror"
                     :class="open ? 'ring-2 ring-blue-500 border-blue-500' : ''">
                    <span x-text="selected || 'اختر الجنسية'" :class="selected ? 'text-gray-900' : 'text-gray-400'"></span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div x-show="open" @click.outside="open=false" x-transition
                     class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
                    <div class="p-2 border-b">
                        <input x-model="search" type="text" placeholder="ابحث عن جنسية..."
                               class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 text-right" @click.stop>
                    </div>
                    <ul class="max-h-48 overflow-y-auto">
                        <template x-for="n in filtered" :key="n">
                            <li @click="selected=n; open=false; search=''"
                                class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-50 text-right"
                                :class="selected===n ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700'"
                                x-text="n"></li>
                        </template>
                        <li x-show="filtered.length===0" class="px-4 py-2 text-sm text-gray-400 text-right">لا توجد نتائج</li>
                    </ul>
                </div>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">مشرف المشرفين</label>
                <select id="area-manager-select" name="area_manager"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('area_manager') border-red-500 @enderror"
                        disabled>
                    <option value="" disabled selected>اختر مشرف المشرفين</option>
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
                <input type="number" step="1" min="0" name="salary"
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

{{-- ===== Flight Ticket ===== --}}
<div class="mt-6 border-t pt-6">
    <label class="inline-flex items-center gap-3 cursor-pointer select-none">
        <input type="checkbox" name="flight_ticket" value="1"
               class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
               {{ old('flight_ticket') ? 'checked' : '' }}>
        <div>
            <p class="text-sm font-semibold text-gray-800"><i class="fas fa-plane me-1 text-blue-500"></i> يستحق تذكرة سفر</p>
            <p class="text-xs text-gray-500">تفعيل إذا كان الموظف يستحق تذكرة سفر سنوية</p>
        </div>
    </label>
</div>

{{-- ===== Sponsorship Employee Section ===== --}}
<div class="mt-6 border-t pt-6"
     x-data="{
         isSponsor: {{ old('is_sponsorship_employee') ? 'true' : 'false' }},
         get isMarried() {
             const sel = document.querySelector('select[name=\'marital_status\']');
             return sel ? sel.value === 'married' : false;
         }
     }"
     x-init="
         $watch('isSponsor', () => {});
         const ms = document.querySelector('select[name=\'marital_status\']');
         if (ms) ms.addEventListener('change', () => { $nextTick(() => {}); });
     ">

    {{-- Checkbox --}}
    <label class="inline-flex items-center gap-3 cursor-pointer mb-4 select-none">
        <input type="checkbox" name="is_sponsorship_employee" value="1"
               x-model="isSponsor"
               class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500"
               {{ old('is_sponsorship_employee') ? 'checked' : '' }}>
        <div>
            <p class="text-sm font-semibold text-gray-800">موظف كفالة</p>
            <p class="text-xs text-gray-500">تفعيل هذا الخيار لإدخال بيانات الكفالة</p>
        </div>
    </label>

    {{-- Sponsorship Extra Fields --}}
    <div x-show="isSponsor" x-cloak x-transition
         class="bg-purple-50 border border-purple-100 rounded-xl p-5 space-y-5">

        <h3 class="text-sm font-bold text-purple-700 uppercase tracking-wide">
            <i class="fas fa-passport me-1"></i> بيانات الكفالة
        </h3>

        {{-- Text fields --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم جواز السفر</label>
                <input type="text" name="passport_number" value="{{ old('passport_number') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                       placeholder="A12345678">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ انتهاء الجواز / الإقامة</label>
                <input type="date" name="id_expiry_date" value="{{ old('id_expiry_date') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم رخصة القيادة</label>
                <input type="text" name="driver_license_number" value="{{ old('driver_license_number') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>
            {{-- Wives count — only when married --}}
            <div x-data="{ married: '{{ old('marital_status') }}' === 'married' }"
                 x-init="document.querySelector('select[name=\'marital_status\']')?.addEventListener('change', e => married = e.target.value === 'married')"
                 x-show="married">
                <label class="block text-sm font-medium text-gray-700 mb-1">عدد الزوجات</label>
                <input type="number" name="wives_count" value="{{ old('wives_count', 1) }}"
                       min="1" max="4"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">المدينة السكنية</label>
                <input type="text" name="residential_city" value="{{ old('residential_city') }}"
                       placeholder="الرياض"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحي السكني</label>
                <input type="text" name="residential_neighborhood" value="{{ old('residential_neighborhood') }}"
                       placeholder="اسم الحي"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>
        </div>

        {{-- Document uploads --}}
        <div class="border-t border-purple-200 pt-4">
            <p class="text-sm font-bold text-purple-700 mb-3"><i class="fas fa-folder-open me-1"></i> أرشفة الوثائق</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">صورة جواز السفر</label>
                    <input type="file" name="doc_passport" accept="image/*,application/pdf"
                           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-400 bg-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">صورة الهوية / الإقامة</label>
                    <input type="file" name="doc_id_card" accept="image/*,application/pdf"
                           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-400 bg-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">صورة رخصة القيادة</label>
                    <input type="file" name="doc_driver_license" accept="image/*,application/pdf"
                           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-400 bg-white">
                </div>
            </div>
        </div>

        {{-- Languages --}}
        <div class="border-t border-purple-200 pt-4">
            <div class="flex items-center justify-between mb-3">
                <label class="text-sm font-medium text-gray-700">اللغات</label>
                <button type="button" onclick="addEmpLangRow()"
                        class="text-xs font-bold text-purple-600 border border-purple-300 bg-white px-3 py-1 rounded-lg hover:bg-purple-50 transition">
                    <i class="fas fa-plus me-1"></i> إضافة لغة
                </button>
            </div>
            <div id="empLangsContainer" class="space-y-2"></div>
            <p id="empLangsEmpty" class="text-xs text-gray-400 text-center py-2">لا توجد لغات مضافة</p>
        </div>
    </div>
</div>

<script>
function addEmpLangRow(lang = '', level = '') {
    const container = document.getElementById('empLangsContainer');
    document.getElementById('empLangsEmpty').style.display = 'none';
    const row = document.createElement('div');
    row.style.cssText = 'display:flex;gap:8px;align-items:center;';
    row.innerHTML = `
        <input type="text" name="lang_name[]" value="${lang}" placeholder="اسم اللغة"
               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-400 focus:border-purple-400">
        <select name="lang_level[]"
                class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-400 bg-white">
            <option value="">المستوى</option>
            <option value="مبتدئ" ${level==='مبتدئ'?'selected':''}>مبتدئ</option>
            <option value="متوسط" ${level==='متوسط'?'selected':''}>متوسط</option>
            <option value="جيد"   ${level==='جيد'?'selected':''}>جيد</option>
            <option value="متقدم" ${level==='متقدم'?'selected':''}>متقدم</option>
            <option value="طليق"  ${level==='طليق'?'selected':''}>طليق</option>
        </select>
        <button type="button"
                onclick="this.parentElement.remove(); if(!document.getElementById('empLangsContainer').children.length) document.getElementById('empLangsEmpty').style.display='';"
                class="w-8 h-8 flex items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-500 hover:bg-red-100 flex-shrink-0">
            <i class="fas fa-times text-xs"></i>
        </button>`;
    container.appendChild(row);
}
</script>

{{-- Uniform Section --}}
<div class="mt-6 border-t pt-6" x-data="{ hasUniform: '{{ old('has_uniform', 'no') }}' }">
    <h3 class="text-base font-semibold text-gray-700 mb-4 text-right">اليونيفورم</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-2 text-right">هل لدى الموظف يونيفورم حالياً؟</label>
            <div class="flex gap-6 justify-end">
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm text-gray-700">لا</span>
                    <input type="radio" name="has_uniform" value="no" x-model="hasUniform"
                           class="w-4 h-4 text-blue-600">
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm text-gray-700">نعم</span>
                    <input type="radio" name="has_uniform" value="yes" x-model="hasUniform"
                           class="w-4 h-4 text-blue-600">
                </label>
            </div>
        </div>
        <div x-show="hasUniform === 'yes'" x-cloak>
            <label class="block text-sm font-medium text-gray-700 mb-1 text-right">عدد التيشيرتات</label>
            <input type="number" name="uniform_tshirt_count" value="{{ old('uniform_tshirt_count', 1) }}" min="0"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg text-right focus:ring-2 focus:ring-blue-500">
        </div>
        <div x-show="hasUniform === 'yes'" x-cloak>
            <label class="block text-sm font-medium text-gray-700 mb-1 text-right">تاريخ الاستلام</label>
            <input type="date" name="uniform_received_at" value="{{ old('uniform_received_at') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('input[name="salary"]').forEach(function (input) {
        input.addEventListener('keydown', function (e) {
            if (e.key === '.' || e.key === ',' || e.key === 'e' || e.key === 'E') {
                e.preventDefault();
            } else if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
                e.preventDefault();
                const current = parseInt(this.value, 10) || 0;
                const next = e.key === 'ArrowUp' ? current + 100 : Math.max(current - 100, 0);
                this.value = next;
                this.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    });

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
                areaManagerSelect.innerHTML = `<option value="" disabled selected>اختر مشرف المشرفين</option>`;

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
            areaManagerSelect.innerHTML = `<option value="" disabled selected>اختر مشرف المشرفين</option>`;

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

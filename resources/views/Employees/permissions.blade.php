  <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">إدارة الصلاحيات والأدوار</h2>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">مدير النظام</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدور</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الصلاحيات</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($roles as $role)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ __($role->name) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500">
                                        @if ($role->permissions->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($role->permissions->take(3) as $permission)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ __($permission->name) }}
                                                    </span>
                                                @endforeach
                                                @if ($role->permissions->count() > 3)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        +{{ $role->permissions->count() - 3 }} أكثر
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400">لا توجد صلاحيات</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-blue-600 hover:text-blue-900 edit-permissions-btn"
                                        data-role-id="{{ $role->id }}"
                                        data-role-name="{{ __($role->name) }}">
                                        تعديل
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

<!-- Permissions Modal -->
 <div class="modal fade" id="permissionsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-gray-50">
                    <h5 class="modal-title font-bold text-gray-800">تعديل الصلاحيات للدور: <span id="modalRoleName" class="text-blue-600"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="permissionsForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($permissions as $group => $groupPermissions)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-3">
                                        <h6 class="font-medium text-gray-700">{{ __($group) }}</h6>
                                        <div class="flex items-center">
                                            <input id="select-all-{{ $group }}" class="form-checkbox h-4 w-4 text-blue-600 select-all-group" type="checkbox" data-group="{{ $group }}">
                                            <label for="select-all-{{ $group }}" class="ml-2 text-sm text-gray-600">تحديد الكل</label>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        @foreach ($groupPermissions as $permission)
                                            <div class="flex items-center">
                                                <input id="perm-{{ $permission->id }}" class="form-checkbox h-4 w-4 text-blue-600 permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->name }}" data-group="{{ $group }}">
                                                <label for="perm-{{ $permission->id }}" class="ml-2 text-sm text-gray-700">{{ __($permission->name) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-gray-50">
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" data-dismiss="modal">إلغاء</button>
                    <button type="button" id="savePermissionsBtn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="submit-text">حفظ التغييرات</span>
                        <span class="spinner-border spinner-border-sm d-none ml-2" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        $(document).ready(function() {
            let currentRoleId = null;

            // Open modal when edit button is clicked
            $('.edit-permissions-btn').click(function() {
                currentRoleId = $(this).data('role-id');
                const roleName = $(this).data('role-name');

                $('#modalRoleName').text(roleName);
                $('#permissionsForm').attr('action', `/admin/roles/${currentRoleId}/permissions`);

                // Reset form and checkboxes
                $('#permissionsForm')[0].reset();
                $('.select-all-group').prop('checked', false);

                // Fetch current permissions for this role
                $.get(`/admin/roles/${currentRoleId}/permissions`, function(response) {
                    if (response.permissions) {
                        response.permissions.forEach(permission => {
                            $(`input[name="permissions[]"][value="${permission}"]`).prop(
                                'checked', true);
                        });

                        // Update "Select All" checkboxes
                        $('.select-all-group').each(function() {
                            const group = $(this).data('group');
                            const allChecked = $(
                                `input.permission-checkbox[data-group="${group}"]:not(:checked)`
                                ).length === 0;
                            $(this).prop('checked', allChecked);
                        });
                    }
                });

                $('#permissionsModal').modal('show');
            });

            // Group selection functionality
            $('.select-all-group').change(function() {
                const group = $(this).data('group');
                const isChecked = $(this).is(':checked');
                $(`input.permission-checkbox[data-group="${group}"]`).prop('checked', isChecked);
            });

            // Update "Select All" when individual permissions change
            $(document).on('change', '.permission-checkbox', function() {
                const group = $(this).data('group');
                const allChecked = $(`input.permission-checkbox[data-group="${group}"]:not(:checked)`)
                    .length === 0;
                $(`input.select-all-group[data-group="${group}"]`).prop('checked', allChecked);
            });

            // Save permissions
            $('#savePermissionsBtn').click(function() {
                const btn = $(this);
                btn.prop('disabled', true);
                btn.find('.submit-text').text('جاري الحفظ...');
                btn.find('.spinner-border').removeClass('d-none');

                $.ajax({
                    url: $('#permissionsForm').attr('action'),
                    type: 'POST',
                    data: $('#permissionsForm').serialize(),
                    success: function(response) {
                        toastr.success(response.message);
                        $('#permissionsModal').modal('hide');
                        // Refresh the page to see changes
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function(xhr) {
                        let errorMessage = 'حدث خطأ أثناء حفظ الصلاحيات';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                        btn.find('.submit-text').text('حفظ التغييرات');
                        btn.find('.spinner-border').addClass('d-none');
                    }
                });
            });
        });
    </script>
@endpush

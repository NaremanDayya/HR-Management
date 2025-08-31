@props([
'action',
'modalTitle',
'confirmText' => 'تأكيد',
'buttonClass' => 'bg-green-600',
'modalId',
'hasForm' => false,
])
<div x-data="bulkActionModal('{{ $modalId }}', '{{ route('employees.action', ['action' => $action]) }}', {{ $hasForm ? 'true' : 'false' }})">
    <!-- Trigger -->
    <button class="dropdown-item text-start" @click="showModal = true">
        {{ $confirmText }}
    </button>

    <!-- Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" x-cloak>
        <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">{{ $modalTitle }}</h2>

            <form :action="formAction" method="POST" @submit.prevent="submitForm">
                @csrf

                <!-- Dynamic Fields Container -->
                <div :id="containerId"></div>

                <!-- Custom Form Fields -->
                <div class="mb-4">
                    {{ $slot }}
                </div>

                <p class="mb-4">هل أنت متأكد من {{ $confirmText }} حسابات الموظفين المحددين؟</p>

                <div class="flex justify-end space-x-3">
                    <button type="button" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400"
                        @click="showModal = false">
                        إلغاء
                    </button>
                    <button type="submit" class="{{ $buttonClass }} text-white px-4 py-2 rounded hover:opacity-90">
                        {{ $confirmText }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

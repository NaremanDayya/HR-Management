@extends('layouts.master')
@section('title', 'بيانات الدخول الخاصة بالموظفين')

@push('styles')
    <style>
        .password-field {
            font-family: 'Courier New', monospace;
            letter-spacing: 0.1em;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }
        .password-container {
    display: flex;
    align-items: center;
    gap: 8px;
}

.password-field {
    font-family: 'Courier New', monospace;
    letter-spacing: 2px;
}

.show-password-btn {
    background: none;
    border: none;
    color: #4f46e5;
    cursor: pointer;
    font-size: 14px;
    padding: 2px 6px;
    border-radius: 4px;
}

.show-password-btn:hover {
    background-color: #f3f4f6;
}

.copy-btn {
    padding: 4px 8px;
    background-color: #f3f4f6;
    color: #4f46e5;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.copy-btn:hover {
    background-color: #e5e7eb;
}

.copy-btn.copied {
    background-color: #10b981;
    color: white;
}

        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
            }

            .no-print {
                display: none !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #e2e8f0;
                padding: 8px;
            }

            thead {
                background-color: #f8fafc;
            }
        }
    </style>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">إدارة بيانات الدخول</h1>
                <p class="mt-1 text-sm text-gray-500">بيانات الدخول الخاصة بالموظفين</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" placeholder="ابحث عن موظف..."
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">إجمالي الموظفين</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ count($credentials) }}</p>
                    </div>
                    <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">نشط اليوم</p>
                        @php
                            $activeEmployees = App\Models\Employee::whereHas('user', function ($query) {
                                $query->where('account_status', 'active');
                        })->count(); @endphp
                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $activeEmployees }}</p>
                    </div>
                    <div class="p-3 rounded-full bg-green-50 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        @php
                            $inActiveEmployees = App\Models\Employee::whereHas('user', function ($query) {
                                $query->where('account_status', 'inactive');
                        })->count(); @endphp
                        <p class="text-sm font-medium text-gray-500">غير نشط</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $inActiveEmployees }}</p>
                    </div>
                    <div class="p-3 rounded-full bg-red-50 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div id="print-area" class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
            <div
                class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">بيانات الدخول للموظفين</h2>
                </div>

                <div class="flex items-center gap-3">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg transition-colors border border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>تصدير</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform"
                                :class="{ 'rotate-180': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                            <div class="py-1">

                                <button @click="exportToPDF(); open = false"
                                    class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    تصدير كملف PDF
                                </button>
                                <button @click="exportToExcel(); open = false"
                                    class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    تصدير كملف Excel
                                </button>
                            </div>
                        </div>
                    </div>

                    <button onclick="window.print()"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg transition-colors border border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="hidden sm:inline">طباعة</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الاسم</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                البريد الإلكتروني</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                كلمة المرور</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة</th>
                            <th scope="col"
                                class="no-print px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($credentials as $index => $credential)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $user = \App\Models\User::where('name',$credential[0])->first();
                                    @endphp
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full"
                                                src="{{$user->personal_image}}"
                                                alt="">
                                        </div>
                                        <div class="mr-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $credential[0] ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $credential[3] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="email-value">{{ $credential[1] ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center gap-2 password-container">
                                        <span class="password-field">••••••••</span>
                                        <button class="show-password-btn text-blue-600 hover:text-blue-800 text-sm"
                                            data-password="{{ $credential[2] ?? '' }}">
                                            إظهار
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        نشط
                                    </span>
                                </td>
                                <td class="no-print px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <button class="copy-btn" data-password="{{ $credential[2] ?? '' }}">
                                            نسخ كلمة المرور
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-lg">لا توجد بيانات متاحة حالياً</p>
                                        <p class="text-sm mt-1">لم يتم العثور على بيانات الدخول للموظفين</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pdf-header hidden print:block">
                <div class="flex items-center justify-between p-4 border-b-2 border-gray-200 mb-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="h-16" />
                        <div class="text-right">
                            <h2 class="text-2xl font-bold text-blue-600">تقرير بيانات الدخول للموظفين</h2>
                            <p class="text-gray-500">تاريخ التقرير: {{ now()->format('Y-m-d') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Show/hide password functionality - fixed version
                document.querySelectorAll('.show-password-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const passwordField = this.previousElementSibling;
                        const password = this.getAttribute('data-password');

                        if (passwordField.textContent === '••••••••') {
                            passwordField.textContent = password;
                            this.textContent = 'إخفاء';

                            // Auto-hide after 10 seconds
                            setTimeout(() => {
                                if (passwordField.textContent === password) {
                                    passwordField.textContent = '••••••••';
                                    this.textContent = 'إظهار';
                                }
                            }, 10000);
                        } else {
                            passwordField.textContent = '••••••••';
                            this.textContent = 'إظهار';
                        }
                    });
                });

                // Keep the existing copy functionality
                document.querySelectorAll('.copy-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const password = this.getAttribute('data-password');
                        navigator.clipboard.writeText(password).then(() => {
                            // Visual feedback
                            this.classList.add('copied');
                            this.textContent = 'تم النسخ!';

                            // Reset after 2 seconds
                            setTimeout(() => {
                                this.classList.remove('copied');
                                this.textContent = 'نسخ كلمة المرور';
                            }, 2000);
                        }).catch(err => {
                            console.error('Failed to copy: ', err);
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection

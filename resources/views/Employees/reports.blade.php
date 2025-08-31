@extends('layouts.master')
@push('styles')
    <style>
        /* General Text Styling */
        .card,
        .card-header,
        .card-body,
        .info-box,
        .info-box-text,
        .info-box-number {
            font-size: 14px;
            font-weight: 700;
            font-family: 'Tahoma', 'Arial', sans-serif;
            color: #000000;
        }

        /* Card styling */
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
            height: 100%;
            background-color: #ffffff;
        }

        .card-header {
            font-size: 14px;
            font-weight: 700;
            padding: 0.75rem 1.25rem;
            background-color: #207fdf;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .rtl-support {
            direction: rtl;
            text-align: right;
        }

        /* Chart containers */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
            margin: 0 auto;
        }

        /* Info boxes styling */
        .info-box {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 0.75rem;
            margin-bottom: 0.75rem;
            text-align: center;
            height: 100%;
        }

        .info-box-text {
            display: block;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .info-box-number {
            display: block;
        }

        .salary-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.25rem;
            border: 1px solid #f3f4f6;
            transition: box-shadow 0.2s ease;
        }

        .salary-card:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .salary-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .salary-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 0.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .chart-container {
                height: 250px;
            }
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 200px;
            }

            .col-md-6 {
                padding: 0 5px;
            }
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <!-- Nationalities Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="font-size: 14px;font-weight: 700;">إحصائيات جنسيات الموظفين
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="nationalitiesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Age Groups Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="font-size: 14px;font-weight: 700;">إحصائيات أعمار الموظفين</div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ageGroupsChart" style="height: 298px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Activeness Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="font-size: 14px;font-weight: 700;">حالة حسابات الموظفين</div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="activenessChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salaries Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="font-size: 14px;font-weight: 700;">إحصائيات رواتب الموظفين</div>
                <div class="card-body">
                     <div class="row mb-4">
        <!-- Total Salary -->
        <div class="col-md-3 col-6 mb-3">
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow h-100">
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">مجموع الرواتب</p>
                    <p class="mt-2 text-2xl font-bold text-indigo-600">
                        {{ number_format($statistics['salaries']['total']) }} <span class="text-lg">ر.س</span>
                    </p>
                </div>
                <div class="mt-3 flex justify-end">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Average Salary -->
        <div class="col-md-3 col-6 mb-3">
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow h-100">
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">متوسط الرواتب</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-600">
                        {{ number_format($statistics['salaries']['average'], 0) }} <span class="text-lg">ر.س</span>
                    </p>
                </div>
                <div class="mt-3 flex justify-end">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Minimum Salary -->
        <div class="col-md-3 col-6 mb-3">
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow h-100">
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">الحد الأدنى</p>
                    <p class="mt-2 text-2xl font-bold text-amber-600">
                        {{ number_format($statistics['salaries']['min']) }} <span class="text-lg">ر.س</span>
                    </p>
                </div>
                <div class="mt-3 flex justify-end">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Maximum Salary -->
        <div class="col-md-3 col-6 mb-3">
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow h-100">
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">الحد الأقصى</p>
                    <p class="mt-2 text-2xl font-bold text-rose-600">
                        {{ number_format($statistics['salaries']['max'], 0) }} <span class="text-lg">ر.س</span>
                    </p>
                </div>
                <div class="mt-3 flex justify-end">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

                    <div class="chart-container">
                        <canvas id="salariesChart" style="height: 235px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Shared chart options
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 12
                            },
                            padding: 20
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            };

            // Nationalities Chart (Pie)
            new Chart(document.getElementById('nationalitiesChart'), {
                type: 'pie',
                data: {
                    labels: {!! json_encode(array_keys($statistics['nationalities']->toArray())) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($statistics['nationalities']->toArray())) !!},
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#8AC249', '#EA5F89', '#00B5AD', '#A667AE'
                        ],
                        borderWidth: 1
                    }]
                },
                options: chartOptions
            });

            // Age Groups Chart (Bar)
            new Chart(document.getElementById('ageGroupsChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($statistics['ageGroups']->toArray())) !!},
                    datasets: [{
                        label: 'أعمار الموظفين',
                        data: {!! json_encode(array_values($statistics['ageGroups']->toArray())) !!},
                        backgroundColor: '#36A2EB',
                        borderWidth: 1
                    }]
                },
                options: {
                    ...chartOptions,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Activeness Chart (Doughnut)
            new Chart(document.getElementById('activenessChart'), {
                type: 'doughnut',
                data: {
                    labels: ['نشط', 'غير نشط'],
                    datasets: [{
                        data: [
                            {{ $statistics['activeness']['active'] }},
                            {{ $statistics['activeness']['inactive'] }}
                        ],
                        backgroundColor: ['#4BC0C0', '#FF6384'],
                        borderWidth: 1
                    }]
                },
                options: chartOptions
            });

            // Salaries Chart (Horizontal Bar)
            new Chart(document.getElementById('salariesChart'), {
                type: 'bar',
                data: {
                    labels: ['إحصائيات الرواتب'],
                    datasets: [{
                            label: 'متوسط الرواتب',
                            data: [{{ $statistics['salaries']['average'] }}],
                            backgroundColor: '#36A2EB',
                            borderWidth: 1
                        },
                        {
                            label: 'أقل راتب',
                            data: [{{ $statistics['salaries']['min'] }}],
                            backgroundColor: '#FFCE56',
                            borderWidth: 1
                        },
                        {
                            label: 'أعلى راتب',
                            data: [{{ $statistics['salaries']['max'] }}],
                            backgroundColor: '#FF6384',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    ...chartOptions,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'قيمة الراتب'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush

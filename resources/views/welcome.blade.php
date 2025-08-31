<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>نظام إدارة الموارد البشرية</title>

    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Tajawal Arabic Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #740e0e;
            --secondary-color: #ED5565;
            --light-bg: #F5F7FA;
            --card-bg: #FFFFFF;
            --text-dark: #2E2E2E;
            --text-light: #44484d;
            --border-radius: 16px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        body {
            font-family: 'Tajawal', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Background with overlay */
        .background-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            overflow: hidden;
        }

        .background-image {
            background-image: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            width: 100%;
            height: 100%;
            filter: brightness(1.2);
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(245, 247, 250, 0.95) 0%, rgba(245, 247, 250, 0.85) 100%);
        }

        /* Navigation */
        .navbar {
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            padding: 15px 0;
            backdrop-filter: blur(5px);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.3rem;
        }

        .navbar-brand img {
            height: 60px;
            margin-left: 10px;
            filter: url(#red-recolor);
            transition: all 0.3s ease;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            position: relative;
        }

        /* Hero Section */
        .hero-section {
            padding: 100px 0;
            position: relative;
        }

        .hero-content {
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
            position: relative;
        }

        .hero-icon {
            font-size: 3.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--text-dark);
        }

        .hero-description {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 30px;
            font-weight: 600;
        }

        /* Buttons */
        .btn {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 30px;
            color: white !important;
            font-weight: 500;
            border-radius: 50px;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(201, 76, 76, 0.3);
        }

        .btn:hover {
            transform: translateY(-3px);
            background-color: #c1a3a3;
            color: black !important;

        }


        /* Features Section */
        .features-section {
            padding: 80px 0;
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(5px);
            border-radius: 30px 30px 0 0;
            margin-top: 40px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .feature-card {
            background-color: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            height: 100%;
            border: none;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background-color: rgba(116, 14, 14, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            color: var(--primary-color);
            font-size: 1.8rem;
        }

        .feature-title {
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--text-dark);
        }

        .feature-text {
            color: var(--text-light);
            font-size: 0.95rem;
            font-weight: 600;
        }

        /* Footer */
        .footer {
            background-color: var(--card-bg);
            padding: 30px 0;
            text-align: center;
            color: var(--text-light);
            font-size: 0.9rem;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.03);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 80px 0;
            }

            .hero-title {
                font-size: 2rem;
            }

            .features-section {
                padding: 60px 0;
                border-radius: 20px 20px 0 0;
            }
        }
    </style>
</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <filter id="red-recolor">
            <feColorMatrix type="matrix"
                values="
        0.8 0.1 0.1 0 0
        0.2 0.6 0.2 0 0
        0.1 0.2 0.6 0 0
        0   0   0   1 0
        " />
        </filter>
    </svg>


    <!-- Background -->
    <div class="background-wrapper">
        <div class="background-image"></div>
        <div class="background-overlay"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('build/assets/img/logo.png') }}" alt="شعار النظام">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto"></ul>
                <ul class="navbar-nav">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="btn">لوحة التحكم</a>
                            </li>
                        @else
                            <li class="nav-item me-2">
                                <a href="{{ route('login') }}" class="btn">تسجيل الدخول</a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h1 class="hero-title">نظام إدارة الموارد البشرية المتكامل</h1>
                    <p class="hero-description">
                        منصة ذكية تقدم حلولاً متكاملة لإدارة الموظفين، الحضور، الرواتب، والمزيد. تم تصميم النظام لتحسين
                        كفاءة عمليات الموارد البشرية وزيادة الإنتاجية.
                    </p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('login') }}" class="btn">تسجيل الدخول</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <div class="container">
                <h2 class="section-title">مميزات النظام</h2>

                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="feature-title">إدارة الموظفين</h3>
                            <p class="feature-text">
                                نظام متكامل لإدارة بيانات الموظفين، الملفات، والمستندات بشكل آمن ومنظم مع إمكانية البحث
                                والتصفية المتقدمة.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3 class="feature-title">الحضور والانصراف</h3>
                            <p class="feature-text">
                                نظام دقيق لتسجيل الحضور والانصراف، متابعة التأخيرات، وإدارة الإجازات بسهولة مع تقارير
                                تفصيلية.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <h3 class="feature-title">إدارة الرواتب</h3>
                            <p class="feature-text">
                                حساب الرواتب تلقائياً مع مراعاة الاستحقاقات والخصومات المختلفة، مع إمكانية إصدار كشوفات
                                الرواتب.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="feature-title">تقارير وإحصائيات</h3>
                            <p class="feature-text">
                                لوحات تحكم تفاعلية مع تقارير وإحصائيات دقيقة تساعد في اتخاذ القرارات الإدارية المستنيرة.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <h3 class="feature-title">نظام التنبيهات</h3>
                            <p class="feature-text">
                                نظام تنبيهات ذكي لإعلام الموظفين والإدارة بالأحداث المهمة والمواعيد النهائية.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h3 class="feature-title">واجهة متحركة</h3>
                            <p class="feature-text">
                                تصميم متجاوب يعمل على جميع الأجهزة من حواسيب، أجهزة لوحية، وهواتف ذكية.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>© 2023 نظام إدارة الموارد البشرية. جميع الحقوق محفوظة.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

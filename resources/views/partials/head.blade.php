<head>
    <!-- Primary Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'Admin Dashboard')">
    <meta name="keywords" content="@yield('keywords', 'dashboard, admin')">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Security & Identification -->
    <meta name="user-id" content="{{ Auth::id() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- Preconnect & Preload Critical Resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Favicons -->
<link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.jpg') }}">
<link rel="shortcut icon" href="{{ asset('assets/img/favicon.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">
@yield('favicon')

    <!-- CSS Reset & Base Normalization -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"
        crossorigin="anonymous">

    <!-- Font Loading (Optimized) -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&family=Amiri&family=Open+Sans:wght@300;400;600;700&family=Nunito:wght@300;400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
        crossorigin="anonymous">

    <!-- Font Awesome (Latest) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
/* =============================================
# CORE STYLES
============================================= */
:root {
    --header-bg: #ffffff;
    --header-height: 70px;
    --sidebar-width: 0px;
    --sidebar-collapsed-width: 80px;
    --sidebar-bg: #ffffff;
    --primary-color: #4f46e5;
    --primary-light: #6366f1;
    --text-color: #1f2937;
    --text-secondary: #6b7280;
    --border-color: #e5e7eb;
    --hover-bg: #f9fafb;
    --transition-speed: 0.3s;
    --dropdown-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --z-sidebar: 100;
    --z-header: 200;
    --z-dropdown: 300;
    --z-modal: 400;
    --z-toast: 500;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    direction: rtl;
}
select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: none !important;
}
html,
body {
    margin: 0;
    scroll-behavior: smooth;
    direction: rtl;
    position: relative;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    font-family: "Open Sans", sans-serif;
    background: #f6f9ff;
    color: #444444;
    padding-top: var(--header-height);
    margin-right: var(--sidebar-width);
}
html.no-sidebar,
body.no-sidebar {
    margin-right: 0 !important;
}
#main.no-sidebar,
#main-table.no-sidebar {
    margin-right: 0 !important;
    width: 100% !important;
}
.table-html.no-sidebar .sidebar {
    display: none !important;
    width: 0 !important;
    padding: 0 !important;
}
.table-html.no-sidebar #main-table {
    width: 100% !important;
    margin-right: 0 !important;
}
.footer.no-sidebar {
    margin-left: 0 !important;
    width: 100% !important;
}

a {
    color: #4154f1;
    text-decoration: none;
    transition: color 0.3s;
}
a:hover {
    color: #717ff5;
}

h1,
h2,
h3,
h4,
h5,
h6 {
    font-family: "Tajawals", sans-serif;
}

/* =============================================
# LAYOUT STRUCTURE
============================================= */

.header-container {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: var(--header-height);
    background-color: grey;
    box-shadow: var(--dropdown-shadow);
    z-index: 1000;
}
.logo {
    display: flex;
    align-items: center;
}

.logo-img {
    height: 4rem;
    width: auto;
}
.sidebar {
    position: fixed;
    direction: rtl;
    top: var(--header-height);
    right: 0;
    bottom: 0;
    width: var(--sidebar-width);
    z-index: 999;
    transition: all 0.3s;
    padding: 20px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #aab7cf transparent;
    box-shadow: 0px 0px 20px rgba(1, 41, 112, 0.1);
    background-color: #fff;
}

#main {
    flex: 1 0 auto;
    margin-top: 90px;
    margin-right:50 px;
    padding: 1rem;
    transition: all var(--transition-speed);
    padding-bottom: 80px;
}
#main-table {
    flex: 1;
    margin-top: 90px;
    padding: 20px 50px;
    transition: all var(--transition-speed);
    padding-bottom: 80px;
    width: 100%;
}
table-html,
table-body {
    width: 100%;
    overflow-x: hidden !important;
}

.table-html.no-sidebar .sidebar {
    display: none !important;
    width: 0 !important;
    padding: 0 !important;
}

.table-html.no-sidebar #main-table {
    width: 100% !important;
    margin-right: 0 !important;
}
.footer {
    margin-left: var(--sidebar-width);
    width: 100%;
    padding: 1rem;
    position: absolute;
    bottom: 0;
    justify-content: center;
}

/* =============================================
# COMPONENTS
============================================= */
/* Toast Notifications */
.toast-container {
    position: fixed;
    top: 100px;
    right: 20px;
    z-index: 1300;
    width: auto;
}

.toast {
    position: relative;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 4px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    animation: slideIn 0.3s ease-out;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    background: #ffebee;
    border-left: 4px solid #f44336;
    color: #d32f2f;
    top: 100px;
}

/* Cards */
.card {
    margin-bottom: 30px;
    border: none;
    border-radius: 5px;
    box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
}

/* Buttons */
.btn {
    transition: all 0.3s;
}

/* Forms */
.form-control {
    border-radius: 0;
    box-shadow: none;
}

/* Sidebar Navigation */
.sidebar-nav {
    padding: 0;
    margin: 0;
    list-style: none;
}

.sidebar-nav li {
    padding: 0;
    margin: 0;
    list-style: none;
}

.sidebar-nav .nav-item {
    margin-bottom: 5px;
}

.sidebar-nav .nav-link {
    display: flex;
    align-items: center;
    font-size: 15px;
    font-weight: 600;
    color: #32ac71;
    transition: 0.3;
    background: #f6f9ff;
    padding: 10px 15px;
    border-radius: 4px;
}

/* =============================================
# UTILITIES
============================================= */
/* Background Colors */
.bg-primary-light {
    background-color: #cfe2ff;
    border-color: #cfe2ff;
}

/* Text Colors */
.text-primary {
    color: #4154f1;
}

/* Spacing */
.mb-3 {
    margin-bottom: 1rem;
}

/* =============================================
# ANIMATIONS
============================================= */
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7);
    }
    70% {
        box-shadow: 0 0 0 4px rgba(74, 222, 128, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(74, 222, 128, 0);
    }
}

/* =============================================
# MEDIA QUERIES
============================================= */
@media (max-width: 1199px) {
    .sidebar {
        right: -250px;
    }

      .table-html.no-sidebar .sidebar {
        right: 0;
        display: none !important;
    }
}

@media (max-width: 768px) {
    .toast-container {
        top: var(--header-height);
        right: 10px;
        left: 10px;
    }

    .toast {
        width: calc(100% - 20px);
    }
}
    </style>
    <!-- Vendor CSS (Combined where possible) -->
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Quill Editor (Load only if needed) -->
    @stack('quill-styles')

    <!-- Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
        crossorigin="anonymous" />

    <!-- Toast Notifications -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" crossorigin="anonymous">

    <!-- Application Styles -->
    @vite(["resources/css/app.css", "resources/js/app.js"])
    @livewireStyles
    @wirechatStyles

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"
        crossorigin="anonymous">
    @stack('multiselect-styles')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer
        crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    @stack('multiselect-scripts')
    <!-- Additional Styles -->
    @stack('styles')

</head>

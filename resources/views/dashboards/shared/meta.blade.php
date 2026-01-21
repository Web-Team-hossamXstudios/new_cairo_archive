
    <meta charset="utf-8">
    <title>أرشيف القاهرة الجديدة</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="admin dashboard template on Themeforest. Perfect for building CRM, CMS, project management tools, and custom web apps with clean UI, responsive design, and powerful features.">
    <meta name="keywords"
        content="Vona, Admin dashboard, Themeforest, HTML template,Shadcn, Bootstrap admin, CRM template, CMS template, responsive admin, web app UI, admin theme, best admin template">
    <meta name="author" content="Coderthemes">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <!-- Theme Config Js -->
    <script src="{{ asset('dashboard/assets/js/config.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset('dashboard/assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css">

    <!-- App css -->
    <link href="{{ asset('dashboard/assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
    {{-- @include('dashboards.shared.rtl') --}}

    <script src="{{ asset('dashboard/assets/plugins/lucide/lucide.min.js') }}"></script>

<style>
    .avatar {
       display: flex;
       justify-content: center;
       align-items: center;
    }
</style>

@include('dashboards.shared.modal-styles')

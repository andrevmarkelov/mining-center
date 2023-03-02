<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @meta_tags
    @php
        $preload_font = 'rel="preload" as="font" type="font/woff2" crossorigin="anonymous"';
        $preload_style = 'rel="stylesheet preload" as="style"';
    @endphp
    <link href="//fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" {!! $preload_style !!}>
    <link href="/admin/libs/fontawesome-free/css/all.min.css" {!! $preload_style !!}>
    <link href="/admin/libs/overlayScrollbars/css/OverlayScrollbars.min.css" {!! $preload_style !!}>
    <link href="/admin/libs/select2/css/select2.min.css" {!! $preload_style !!}>
    <link href="/admin/libs/select2-bootstrap4-theme/select2-bootstrap4.min.css" {!! $preload_style !!}>
    <link href="/admin/libs/datatables-bs4/css/dataTables.bootstrap4.min.css" {!! $preload_style !!}>
    <link href="/admin/libs/datatables-responsive/css/responsive.bootstrap4.min.css" {!! $preload_style !!}>
    <link href="/admin/libs/summernote/summernote-bs4.css" {!! $preload_style !!}>
    <link href="/admin/libs/toastr/toastr.min.css" {!! $preload_style !!}>
    <link href="/admin/libs/icheck-bootstrap/icheck-bootstrap.min.css" {!! $preload_style !!}>
    <link href="/admin/libs/filepond/filepond.min.css" {!! $preload_style !!}>
    <link href="/admin/libs/filepond/filepond-plugin-file-poster.css" {!! $preload_style !!}>
    <link href="/admin/css/adminlte.min.css" {!! $preload_style !!}>
    <link href="//cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.3.0/css/flag-icon.min.css" {!! $preload_style !!}>
    <link href="/admin/css/main.css" {!! $preload_style !!}>
</head>
<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed">

<script>
    const lang = JSON.parse('{!! json_encode([]) !!}');
</script>

<div class="wrapper">
    @include('admin.inc.header')

    @include('admin.inc.aside')

    <div class="content-wrapper">
        @yield('content')
    </div>

    @include('admin.inc.footer')
</div>

<script src="/admin/libs/jquery/jquery.min.js"></script>
<script src="/admin/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/admin/libs/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="/admin/libs/select2/js/select2.full.min.js"></script>
<script src="/admin/libs/datatables/jquery.dataTables.min.js"></script>
<script src="/admin/libs/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/admin/libs/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/admin/libs/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/admin/libs/summernote/summernote-bs4.js"></script>
<script src="/admin/libs/summernote/lang/summernote-ru-RU.min.js"></script>
<script src="/admin/libs/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="/admin/libs/toastr/toastr.min.js"></script>
{{-- <script src="/admin/libs/moment/moment.min.js"></script> --}}
<script src="/admin/libs/filepond/filepond-plugin-file-poster.js"></script>
<script src="/admin/libs/filepond/filepond.min.js"></script>
<script src="/admin/libs/inputmask/jquery.inputmask.min.js"></script>
<script src="/admin/js/adminlte.min.js"></script>
<script src="/admin/js/main.js"></script>

@include('admin.inc.messages')

@yield('script')

</body>
</html>

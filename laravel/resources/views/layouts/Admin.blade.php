<!doctype html>
<html lang="en">
<head>
    @include('includes.MetaData')
    @include('includes.Stylesheets')

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="/js/external/html5shiv.js"></script>
    <script src="/js/external/respond.min.js"></script>
    <![endif]-->
</head>
<body class="<?php
if (isset($_controller)) {
    echo $_controller;
    echo isset($_action) ? ' ' . $_controller . '-' . $_action . ' ' : '';
}
?>">
<section id="container" class="">
    @include('includes.AdminNavBar')
    @include('includes.AdminSidebar')
    @yield('content')
    @include('includes.AdminFooter')
</section>
@include('includes.Scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">

@include('common.head')

<body class="page-header-fixed page-sidebar-closed-hide-logo">

    <div class="wrapper">
        <!-- BEGIN HEADER -->
            @include('common.header')
        <!-- END HEADER -->

        @yield('content')

    </div>

	@include('common.footer')

	@yield('scripts')
</body>
</html>

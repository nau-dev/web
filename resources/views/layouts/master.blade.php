<!DOCTYPE html>
<html>

    @include('partials.head')

    <body>

        <div id="mainwrapper">
            <header>
                @section('header')
                    @include('partials.header')
                @show
            </header>

            <main class="clearfix">
                @yield('content')
            </main>
        </div>

        <footer>
            @section('footer')
                @include('partials.footer')
            @show
        </footer>

    </body>

    @include('partials.javascripts')
    @stack('scripts')

</html>

<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        @stack('scripts')
        <title>@yield('title')</title>
        @yield('jqueryScript')
    </head>
    <body>
    	<nav class='navbar navbar-default navbar-fixed-top'>
    		<div class='container-fluid'>
    			<div class='navbar-header'>
    				<a class='navbar-brand' href='{{ route('homeBook') }}'>@yield('title')</a>
    			</div>
    		</div>
    	</nav>

    	<div class='container'>
    		<div id='leftSide' class='col-md-2'>
                @yield('searchForm')
    		</div>

    		<div class='col-md-10'>
                <div class='col-md-7'>
                    @yield('filterForm')
                </div>                
    			<div class='text-right'>
    				@yield('addBookBtn')
    			</div>
    			<div class='panel panel-default text-center'>
    				<div class='panel-body'>
                        @yield('bookTable')
    				</div>
    			</div>
			    <div class='fluid text-right'>
                    @yield('paginator')
				</div>
                    @yield('noResult')
    		</div>


    		@yield('addBookModal')

    	</div>
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link href='{{ URL::asset('css/bootstrap.min.css') }}' rel='stylesheet'>
        <link href='{{ URL::asset('css/bootstrap-select.min.css') }}' rel='stylesheet'>
        <script src='{{ URL::asset('js/jquery.min.js') }}'></script>
        <script src='{{ URL::asset('js/bootstrap.min.js') }}'></script>
        <script src='{{ URL::asset('js/bootstrap-select.min.js') }}'></script>
        <title>@yield('title')</title>

        <script>
        	
        	$(document).ready(function(){

    			var title;
    			var author;
    			var genre;
    			var section;

    			$('.required').hide();

    			$('.selectpicker').selectpicker();
				$.ajaxSetup({
					headers:{
						'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
					}
				});

        		$('#addBookBtn').click(function() {

	    			title = $('#title').val();
	    			author = $('#author').val();
	    			genre = $('#genre').val();
	    			section = $('#section').val();

	    			if( title == '' ) {
	    				$('#titleRequired').show();
	    			}else if( author == '' ) {
	    				$('#authorRequired').show();
	    			}else if( genre == '' ) {
	    				$('#genreRequired').show();
	    			}else if( section == '' ) {
	    				$('#sectionRequired').show();
	    			}else {
		    			var bookData = {
		    				title:title,
		    				author:author,
		    				genre:genre,
		    				section:section
		    			};


	        			//$.post('book/add',bookData,function(data){ 
	        			$.post('{{ route('addBook') }}',bookData,function(data){ 
	        				//console.log(data);
	        				$('#addBookModal').modal('hide');
	        				$("#bookTable").load(location.href + " #bookTable");
	        				$('#title').val('');
	        				$('#author').val('');
	        				$('#genre').val('');
	        				$('#section').val('');
	        			});

        			}
        		});
        		
        		$('#cancelAddBook').click(function(){
    				$('#title').val('');
    				$('#author').val('');
    				$('#genre').val('');
    				$('#section').val('');        			
        			$('.required').hide();
        		});

        		@foreach( $book as $id )
        			$('#borrowBtn{{ $id->id }}').click(function( ){

        				var bookID = $('#borrowTxt{{ $id->id }}').val();

        				$.post('{{ route('updateStatus',['id' => $id->id]) }}',bookID,function(){
        					window.location = "{{ route('homeBook') }}"
        				})
        			});
        		@endforeach
				
        	});

        </script>

        <style>

        	#addBtn {
        		margin-bottom:1%;
        	}

        	.container {
        		margin-top:5%;
        	}

        	#leftSide {
        		margin-top:5%;
        	}

        	#searchBox {
        		margin-bottom:10%;
        	}

        	#searchBtn {
        		margin-top:7%;
        	}

        	.required {
        		color:red;
        		font-size:90%;
        	}

        </style>

    </head>
    <body>

    	<nav class='navbar navbar-default navbar-fixed-top'>
    		<div class='container-fluid'>
    			<div class='navbar-header'>
    				<a class='navbar-brand' href='{{ route('homeBook') }}'>Rio's Library</a>
    			</div>
    		</div>
    	</nav>

    	<div class='container'>
    		<div id='leftSide' class='col-md-2'>
                @yield('searchForm')
    		</div>

    		<div class='col-md-8'>
    			<div class='text-right'>
    				<div class='col-md-1'>
                        @yield('filterForm')
    				</div>
    				<button id='addBtn' class='btn btn-default' data-toggle='modal' data-target='#addBookModal'>Add Book</button>
    			</div>
    			<div class='panel panel-default text-center'>
    				<div class='panel-body'>
                        @yield('bookTable')
    				</div>
    			</div>
			    <div class='fluid text-right'>
                    @yield('paginator')
				</div>
    		</div>

    		<div class='col-md-2'>
			    			
    		</div>

    		@yield('addBookModal')

    	</div>
    </body>
</html>

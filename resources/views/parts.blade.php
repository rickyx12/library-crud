@extends('layouts.book');

@section('title','Rio Library');

@push('scripts')

	<link href='{{ URL::asset('css/custom.css') }}' rel='stylesheet'>
	<link href='{{ URL::asset('css/bootstrap.min.css') }}' rel='stylesheet'>
	<link href='{{ URL::asset('css/bootstrap-select.min.css') }}' rel='stylesheet'>
	<link href='{{ URL::asset('css/sweetalert.css') }}' rel='stylesheet'>
	<script src='{{ URL::asset('js/jquery.min.js') }}'></script>
	<script src='{{ URL::asset('js/bootstrap.min.js') }}'></script>
	<script src='{{ URL::asset('js/bootstrap-select.min.js') }}'></script>
	<script src='{{ URL::asset('js/sweetalert.min.js') }}'></script>

@endpush

@section('jqueryScript')

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

    		//if there is no result
    		@if($book == "" )
    			$('#noResult').show();
    		@endif

    		@foreach( $book as $id )

    			var bookID = '{{ $id->id }}';

    			$("#noResult").hide();

    			$('#borrowBtn{{ $id->id }}').click(function( ){

    				$.post('{{ route('updateStatus',['id' => $id->id]) }}',bookID,function(){
    					//window.location = "{{ route('homeBook') }}"
    					location.reload();
    				})
    			});



    			$('#delBtn{{ $id->id }}').click(function(){
    				if( '{{ $id->status }}' == 'borrowed' ) 
    				{
    					swal("Sorry", "The book should be return first before you can delete it.", "error");
    				}else 
    				{
	    				swal({
	    					title:'Delete?',
	    					text:' "{{ $id->title }}" - {{ $id->author }}',
	    					type:'warning',
	    					showCancelButton:true,
	    					confirmButtonColor:"#DD6B55",
	    					confirmButtonText: "Yes, delete it!",
	    					closeOnConfirm: false,
	    					showLoaderOnConfirm: true,
	    				},
						function() { 
							setTimeout(function(){   
								$.post('{{ route('delete',['id' => $id->id]) }}',bookID,function(){
									swal({
										title:'Deleted!',
										text:'{{ $id->title }} - {{ $id->author }} has been delete.',
										type:'success'
									},function(){
										location.reload();	
									});
								});
							},2500);
						});
    				}
    			});
	    		

    		@endforeach
			
    	});

    </script>

@endsection

@section('addBookBtn');
	<button id='addBtn' class='btn btn-info' data-toggle='modal' data-target='#addBookModal'>Add Book</button>
@endsection

@section('addBookModal')

	<!--New Book Modal-->
	<div id='addBookModal' class='modal fade'>
	    <!--<form action='book/add' method='post'>-->
	    <div class='modal-dialog'>
	        <div class='modal-content'>
	            <div class='modal-header'>
	                <h5 class='modal-title'>New Book</h5>
	            </div>
	            <div class='modal-body'>
	                
	                <div class='form-group'>
	                    <label for='title'>Title</label>
	                    <input type='text' class='form-control' id='title' name='title' class='tooltip' autocomplete='off'>
	                    <span id='titleRequired' class='required'>*Title is a Required Field</span>
	                </div>

	                <div class='form-group'>
	                    <label for='author'>Author</label>
	                    <input type='text' class='form-control' id='author' name='author' autocomplete='off'>
	                    <span id='authorRequired' class='required'>*Author is a Required Field</span>
	                </div>

	                <div class='form-group'>
	                    <label for='genre'>Genre</label>
	                    <select class='form-control' id='genre' name='genre'>
	                        <option></option>
	                        <option>Horror</option>
	                        <option>Romance</option>
	                        <option>Thriller</option>
	                    </select>
	                    <span id='genreRequired' class='required'>*Please Select a Genre</span>
	                </div>

	                <div class='form-group'>
	                    <label for='section'>Section</label>
	                    <select class='form-control' id='section' name='section'>
	                        <option></option>
	                        <option>Circulation</option>
	                        <option>Periodical Section</option>
	                        <option>General Reference</option>
	                        <option>Childrens Section</option>
	                        <option>Fiction</option>
	                    </select>
	                    <span id='sectionRequired' class='required'>*Please Select a Section</span>
	                </div>
	            </div>
	            <div class='modal-footer'>
	                <button id='cancelAddBook' class='btn btn-default' data-dismiss='modal'>Cancel</button>
	                <button id='addBookBtn' class='btn btn-success'>Add Book</button>
	            </div>
	        </div>
	    </div>   
	    <!--</form>-->          
	</div>

@endsection


@section('searchForm')

	{!! Form::open(['route' => 'searchText','method' => 'GET']) !!}
		 {{ csrf_field() }}
		<input type='text' id='searchBox' name='search' class='form-control' placeholder='{{ $searchHeader }}' autocomplete='off'>   		
		<div class='row'>
			{{ Form::radio('searchOption', 'title',true,['checked' => true]) }} Title
		</div>
		<div class='row'>
			{{ Form::radio('searchOption', 'author') }} Author
		</div>
		<div class='row'>
			{{ Form::radio('searchOption', 'section') }} Section
		</div>
		<div id='searchBtn'>
			<button id='searchBtn' class='btn btn-info btn-block' type='submit'>Search</button>
		</div>
	{!! Form::close() !!}

@endsection


@section('filterForm')

	{!! Form::open(['route' => 'filter','method' => 'GET']) !!}
		{{ csrf_field() }}
		<select name='filterOption' class='selectpicker show-menu-arrow' title='{{ $filterHeader }}' onchange='this.form.submit();'>
			<optgroup label='Genre'>
				<option value='genre-Horror'>Horror</option>
				<option value='genre-Romance'>Romance</option>
				<option value='genre-Thriller'>Thriller</option>
			</optgroup>
			<optgroup label='Section'>
				<option value='section-Circulation'>Circulation</option>
				<option value='section-Periodical Section'>Periodical Section</option>
				<option value='section-General Reference'>General Reference</option>
				<option value='section-Childrens Section'>Childrens Section</option>
				<option value='section-Fiction'>Fiction</option>
			</optgroup>
			<option value='all'>Show All</option>
		</select>
	{!! Form::close() !!}

@endsection

@section('bookTable')

	<table id='bookTable' class='table table-hover table-responsive'>
		<thead>
			<tr>
				<td>Title</td>
				<td>Author</td>
				<td>Genre</td>
				<td>Section</td>
				<td></td>
				<td></td>
			</tr>
		</thead>
		<tbody>
			@foreach( $book as $b )
				<tr>
					<td>{{ $b->title }}</td>
					<td>{{ $b->author }}</td>
					<td>{{ $b->genre }}</td>
					<td>{{ $b->section }}</td>
					<td>
						@if( $b->status != 'borrowed' )
							<button id='borrowBtn{{ $b->id }}' class='btn btn-success btn-sm'>Borrow</button>
						@else
							<button id='borrowBtn{{ $b->id }}' class='btn btn-danger btn-sm'>Return</button>
						@endif
					</td>
					<td>
						<button id='delBtn{{ $b->id }}' class='btn btn-default btn-sm'>Delete</button>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection


@section('paginator')

	{{-- 
		although i can put the parameter directly to the appends() of links() i choose to enclosed within php directive for better readability. 
	--}}
	@php
		$parameters = array(
				'filterOption' => $filterOption,
				$filterOption => $filterValue,
				'search' => $searchValue,
				'searchOption' => $searchOption
			);
	@endphp
	{{ $book->appends($parameters)->links() }}

@endsection

@section("noResult")
	<div id="noResult" class="alert alert-danger" role="alert">No Matching Results Found.</div>
@endsection 
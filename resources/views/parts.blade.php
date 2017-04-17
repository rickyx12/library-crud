@extends('layouts.book');

@section('title','Rio Library');

@push('scripts')

	<link href='{{ URL::asset('css/custom.css') }}' rel='stylesheet'>
	<link href='{{ URL::asset('css/bootstrap.min.css') }}' rel='stylesheet'>
	<link href='{{ URL::asset('css/bootstrap-select.min.css') }}' rel='stylesheet'>
	<link href='{{ URL::asset('css/sweetalert.css') }}' rel='stylesheet'>
	<link href='{{ URL::asset('css/font-awesome/css/font-awesome.min.css') }}' rel='stylesheet'>
	<script src='{{ URL::asset('js/jquery.min.js') }}'></script>
	<script src='{{ URL::asset('js/bootstrap.min.js') }}'></script>
	<script src='{{ URL::asset('js/bootstrap-select.min.js') }}'></script>
	<script src='{{ URL::asset('js/sweetalert.min.js') }}'></script>

@endpush

@section('jqueryScript')

    <script>
   
	    //global var because default value will be change by filter() and will be use by search()
	    var filterOptionFormatter = 'anything';

	    function addBook() {

    		//$('#addBookBtn').click(function() {
    		$("body").on('click','#addBookBtn',function() {
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
	    			var booksData = {
	    				title:title,
	    				author:author,
	    				genre:genre,
	    				section:section
	    			};


        			//$.post('book/add',bookData,function(data){ 
        			$.post('{{ route('addBook') }}',booksData,function(data) { 
        				$('#addBookModal').modal('hide');
			    		var url = '{{ url("book/all") }}?page=1';
			    		bookData(url,pageActive,'all');
			    		swal('Book Added',$('#title').val()+' - '+$('#author').val()+' is now added in the catalogue. You will be now redirected to the first page of all books.','success');
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

    		console.log($('.allBook').attr('href'));

	    }

	    function deleteBook(id,title,author,status,page,url) {

			$('body').on('click','#delBtn'+id,function() {
		
				if( status == 'borrowed' ) {
					swal("Sorry", "The book should be return first before you can delete it.", "error");
				}else 
				{
    				swal({
    					title:'Delete?',
    					text:' "'+title+'" - '+author+'',
    					type:'warning',
    					showCancelButton:true,
    					confirmButtonColor:"#DD6B55",
    					confirmButtonText: "Yes, delete it!",
    					closeOnConfirm: false,
    					showLoaderOnConfirm: true,
    				},
					function() { 
						setTimeout(function(){   
							$.post('{{ route('delete') }}',{ id:id },function(){
								swal({
									title:'Deleted!',
									text:' '+title+' - '+author+' has been deleted.',
									type:'success'
								},function(){
									bookData(url,page,'all');	
								});
							});
						},2500);
					});
				}
			});
	    }


	    function updateBook(id,title,author,status,page,pageType) {

			$('body').on('click','#borrowBtn'+id,function() {	
				
				//$.post('{{ route('updateStatus') }}',{ id:id, status:status },function() {

					if( status == 'borrowed' ) 
					{
	    				swal({
	    					title:'Return?',
	    					text:' "'+title+'" - '+author+' will be tag as Return',
	    					type:'warning',
	    					showCancelButton:true,
	    					confirmButtonColor:"#DD6B55",
	    					confirmButtonText: "Yes",
	    					closeOnConfirm: false,
	    					showLoaderOnConfirm: true,
	    				},
						function() { 
							setTimeout(function(){   
								$.post('{{ route('updateStatus') }}',{ id:id, status:status },function() {
									swal({
										title:'Return!',
										text:' '+title+' - '+author+' has been returned.',
										type:'success'
									},function(){
										showBook(page);
									});
								});
							},2000);
						});
					}else 
					{
	    				swal({
	    					title:'Borrow?',
	    					text:' "'+title+'" - '+author+' will be tag as borrowed',
	    					type:'warning',
	    					showCancelButton:true,
	    					confirmButtonColor:"#DD6B55",
	    					confirmButtonText: "Yes",
	    					closeOnConfirm: false,
	    					showLoaderOnConfirm: true,
	    				},
						function() { 
							setTimeout(function(){   
								$.post('{{ route('updateStatus') }}',{ id:id, status:status },function() {
									swal({
										title:'Borrowed!',
										text:' '+title+' - '+author+' is now tag as borrowed.',
										type:'success'
									},function() {
										
										if( pageType == 'all' ) {
											
											var url = '{{ url("book/all") }}?page=';
											bookData(url+page,page,'all');

										}else if( pageType == 'filter' ) {

											var filterOption = $('#filterOption').val();
											var url = '{{ url("book/filter/page") }}/'+filterOption+'/'+page+'?page='+page;
											bookData(url,page,'filter');											
										
										}else if( pageType == 'search' ) {

								    		var searchFormatter = '';
								    		var search = $('#searchBox').val();
											var searchOption = $('input[class=searchOption]:checked').map(function(){
																	return this.value;
																}).get();

											/**
											* if no value in search textbox then make value as "all".
											* in the backend "all" means show all
											*/
											if( search == '' ) {
												searchFormatter = 'all';
											}else {
												searchFormatter = search;
											}

											var url = '{{ url("book/search") }}/'+filterOptionFormatter+'/'+searchOption+'/'+searchFormatter+'?page='+page;
								    		bookData(url,page,'search');

										}else { 
											console.log('error bes!');
										}																				
									});
								});
							},2000);
						});
					}
				//})
			});
	    }



		//show search matches result
	    function search(page) {
	    	$("body").on('click','#searchBtn',function() {

	    		var searchFormatter = '';
	    		var search = $('#searchBox').val();
	    		var filterOption = $('#filterOption').val();
				var searchOption = $('input[class=searchOption]:checked').map(function(){
										return this.value;
									}).get();
				/**
				* if no value in search textbox then make value as "all".
				* in the backend "all" means show all
				*/
				if( search == '' ) {
					searchFormatter = 'all';
				}else {
					searchFormatter = search;
				}

				var url = '{{ url("book/search") }}/'+filterOptionFormatter+'/'+searchOption+'/'+searchFormatter+'?page=';
				bookData(url+page,page,'search');
	    	});

	    	//paginator pages
	    	$("body").on('click','.searchBook',function(e) {
	    		e.preventDefault();
	    		var searchFormatter = '';
	    		var search = $('#searchBox').val();
				var searchOption = $('input[class=searchOption]:checked').map(function(){
										return this.value;
									}).get();
	    		var clickedPage = $(this).text();

				/**
				* if no value in search textbox then make value as "all".
				* in the backend "all" means show all
				*/
				if( search == '' ) {
					searchFormatter = 'all';
				}else {
					searchFormatter = search;
				}

				//var url = '{{ url("book/search") }}/'+filterOptionFormatter+'/'+searchOption+'/'+searchFormatter+'?page='+clickedPage;
	    		var url = $(this).attr('href');
	    		bookData(url,clickedPage,'search');
	    		console.log(filterOptionFormatter);
	    	});

	    }



	    //show filtered book
	    function filter(page) {
			$("body").on('change','#filterOption',function(e) {
				
				//local variable because value is available only on change event.
				var filterOption = $(this).val(); 
				filterOptionFormatter = filterOption;
				var url = '{{ url("book/filter/page") }}/'+filterOption+'?page=';
				bookData(url+page,page,'filter');		
			});

			//paginator pages
			$("body").on('click','.filteredBook',function(e){
				e.preventDefault();
				//value came from the previous onchange event.
				var filterOption = $('#filterOption').val();
				var clickedPage = $(this).text();
				//var url = '{{ url("book/filter/page") }}/'+filterOption+'/'+clickedPage+'?page='+clickedPage;
				var url = $(this).attr('href');
				bookData(url,clickedPage,'filter');
			});

	    }

	    //show all book
    	function showBook(page) {
    		var url = '{{ url("book/all") }}?page=';
    		bookData(url+page,page,'all');
    		
    		$("body").on('click','.allBook',function(e) {
    			e.preventDefault();
    			clickedPage = $(this).text();
    			bookData(url+clickedPage,clickedPage,'all');
    		});

    	}

    	//json parser to output in the table by injecting the JSON 
    	function bookData(json,page,pageType) {
    		$.getJSON(json,'',function(result) {
    			var tableData = '';

    			$.each(result['books']['data'],function(i,field) {		
    				tableData += '<tr>';
    				tableData += '<td>'+field.title+'</td>';
    				tableData += '<td>'+field.author+'</td>';
    				tableData += '<td>'+field.genre+'</td>';
    				tableData += '<td>'+field.section+'</td>';
    				if( field.status != 'borrowed' ) {
    					tableData += '<td><i class="fa fa-check"></i></td>';
    					tableData += '<td><button id="borrowBtn'+field.id+'" class="btn btn-success btn-sm">Borrow</button></td>';
    				}else {
    					tableData += '<td><i class="fa fa-times"></i></td>';
    					tableData += '<td><button id="borrowBtn'+field.id+'" class="btn btn-danger btn-sm">Return</button></td>';
    				}
    				tableData += '<td><button id="delBtn'+field.id+'" class="btn btn-default btn-sm">Delete</button></td>';
    				tableData += '</tr>';

    				updateBook(field.id,field.title,field.author,field.status,page,pageType);
    				deleteBook(field.id,field.title,field.author,field.status,page,json);

    			});

    			$('tbody').html(tableData);
				$('#pagination').html(result['pagination']);    	

				//show an alert box if there is no books to show.
				if( result['books']['data'] == '' ) {
					$('#noResult').show();
				}else {
					$('#noResult').hide();
				}

    		});
    	}

    	$(document).ready(function(){

			var title;
			var author;
			var genre;
			var section;

			$('#noResult').hide();
			$('.required').hide();
			
			pageActive = $('.active').text();

			//will run upon onchange of #filterOption
			filter(pageActive);

			//will run upon onclick of #searchBtn
			search(pageActive);
			
			//run onload.
			showBook(pageActive);

			addBook();

			$('.selectpicker').selectpicker();
			$.ajaxSetup({
				headers:{
					'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
				}
			});
			
    	});

    </script>

@endsection

@section('addBookBtn')
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

	{{-- Form::open(['route' => 'searchText','method' => 'GET']) --}}
		 {{-- csrf_field() --}}
		<input type='text' id='searchBox' name='search' class='form-control' placeholder='{{ $searchHeader  }}' autocomplete='off'>   		
		<div class='row'>
			<input type='radio' value='title' name='search' class='searchOption' checked="true"> Title
		</div>
		<div class='row'>
			<input type='radio' value='author' name='search' class='searchOption'> Author
		</div>
		<div class='row'>
			<input type='radio' value='section' name='search' class='searchOption'> Section
		</div>
		<div id='searchBtn'>
			<button id='searchBtn' class='btn btn-info btn-block' type='submit'>Search</button>
		</div>
	{{-- Form::close() --}}

@endsection


@section('filterForm')

	{{-- Form::open(['route' => 'filter','method' => 'POST']) --}}
		{{-- csrf_field() --}}
		<select id='filterOption' name='filterOption' class='selectpicker show-menu-arrow' title='{{ $filterHeader }}'>
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
			<option value='anything'>Show All</option>
		</select>
	{{-- Form::close() --}}

@endsection

@section('bookTable')

	<table id='bookTable' class='table table-hover table-responsive'>
		<thead>
			<tr>
				<td>Title</td>
				<td>Author</td>
				<td>Genre</td>
				<td>Section</td>
				<td><h6>Available</h6></td>
				<td></td>
				<td></td>
			</tr>
		</thead>
		<tbody>

			{{--
			@foreach( $book as $b )
				<tr class='bookData'>
					<td>{{ $b->title }}</td>
					<td>{{ $b->author }}</td>
					<td>{{ $b->genre }}</td>
					<td>{{ $b->section }}</td>
					<td align='center'>
						@if( $b->status != 'borrowed' ) 
							<i class="fa fa-check" aria-hidden="true"></i>
						@else
							<i class="fa fa-times" aria-hidden="true"></i>	
						@endif
					</td>
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
			--}}
		</tbody>
	</table>

@endsection


@section('paginator')
	<a id='pagination'> </a>
@endsection

@section("noResult")
	<div id="noResult" class="alert alert-danger" role="alert">No Matching Results Found.</div>
@endsection 
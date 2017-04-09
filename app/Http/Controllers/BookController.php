<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Book as book;


class BookController extends Controller
{

	private $_filter = 'Filter';
	private $_search = 'Search';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$book = book::paginate(5);

    	$viewParameter = array(
    			'book' => $book,
    			'filterHeader' => $this->_filter,
    			'filterOption' => '',
    			'filterValue' => '',
    			'searchHeader' => $this->_search,
    			'searchOption' => '',
    			'searchValue' => ''
    		);

        return view('parts')->with($viewParameter);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    	$book = new Book();

    	$book->title = $request->title;
    	$book->author = $request->author;
    	$book->genre = $request->genre;
    	$book->section = $request->section;
    	$book->status = '';

    	$book->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	//
    }

    /**
    *for searching
    */
    public function search(Request $request) 
    {

    	if( $request->search != '' || $request->search != null ) 
    	{

        	$books = DB::table('books')
        		->where($request->searchOption,'like',$request->search.'%')
        		->where('status','=','')
        		->paginate(5);

        	$this->_search = $request->search;	
        
        }else {
        	return redirect()->route('homeBook');
        }
        
        $viewParameter = array(
        		'book' => $books,
        		'filterHeader' => $this->_filter,
        		'filterOption' => '',
        		'filterValue' => '',
        		'searchHeader' => $this->_search,
        		'searchOption' => $request->searchOption,
        		'searchValue' => $request->search
        	);

        return view('parts')->with($viewParameter);
        
    }

    /**
    *for filter
    */
    public function filter(Request $request) 
    {


    	if( $request->filterOption == 'all' ) 
    	{
    		
    		return redirect()->route('homeBook');
    
    	}else {

    	/**
    	* SAMPLE
    	* assume $request: genre-Horror
    	* $filter[0] = genre
    	* $filter[1] = Horror
		*/ 

    	$filter = preg_split ("/\-/",$request->filterOption);

		$book = book::where($filter[0],$filter[1]) 
				->where('status','')
				->orderBy('title','ASC')
				->paginate(5);

		$this->_filter = $filter[1];

    	}

    	$viewParameter = array(
    			'book' => $book,
    			'filterHeader' => $this->_filter,
    			'filterOption' => 'filterOption',
    			'filterValue' => $request->filterOption,
    			'searchHeader' => $this->_search,
    			'searchOption' => '',
    			'searchValue' => ''
    			);
    	return view('parts')->with($viewParameter);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    	
        $book = book::find($id);

        if( $book->status != 'borrowed' ) {
        	$book->status = 'borrowed';
    	}else {
    		$book->status = '';
    	}

        $book->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = book::find($id);
        $book->delete();
    }
}

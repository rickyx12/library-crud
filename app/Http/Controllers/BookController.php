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

    	$viewParameter = array(
    			'filterHeader' => $this->_filter,
    			'filterOption' => 'all',
    			'filterValue' => '',
    			'searchHeader' => $this->_search,
    			'searchOption' => '',
    			'searchValue' => ''
    		);

        return view('parts')->with($viewParameter);
    }


    //will call upon body onload
    public function all() {

        $books = book::orderBy('title','ASC')
                ->paginate(5);

        $parameters = array(
                'books' => $books,
                'pagination' => (string) $books->links()
            );

        return response()->json($parameters);
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
    public function search($filterOption,$searchOption,$search = '') 
    {

    	if( $search != 'all' ) 
    	{  
            if( $filterOption == 'anything' ) 
            {
               $books = DB::table('books')
                ->where($searchOption,'like',$search.'%')
                ->where('status','=','')
                ->orderBy('title','ASC')
                ->paginate(5);
            }else 
            {
               $filter = preg_split ("/\-/",$filterOption); 
        	   $books = DB::table('books')
        		->where($searchOption,'like',$search.'%')
                ->where($filter[0],$filter[1])
        		->where('status','=','')
                ->orderBy('title','ASC')
        		->paginate(5);
            }

        	$this->_search = $search;	

             $viewParameter = array(
                'books' => $books,
                'filterHeader' => $this->_filter,
                'filterOption' => '',
                'filterValue' => '',
                'searchHeader' => $this->_search,
                'searchOption' => $searchOption,
                'searchValue' => $search,
                'pagination' => (string) $books->links()
            );

            //return view('parts')->with($viewParameter);
            return response()->json($viewParameter);            
        
        }else {
            if( $filterOption != 'anything' ) 
            {
                $filter = preg_split ("/\-/",$filterOption);
                $books = book::where($filter[0],$filter[1])
                            ->paginate(5);
            }else 
            {
                $books = book::paginate(5);
            }  


            $parameters = array(
                'books' => $books,
                'pagination' => (string) $books->links()
            );


            return response()->json($parameters);
        }               
    }

    /**
    * for filter route
    */
    public function filter($filterOption,$page = '') 
    {

    	if( $filterOption == 'anything' ) 
    	{
            $books = book::orderBy('title','ASC')
                        ->paginate(5);

            $parameters = array(
                'books' => $books,
                'pagination' => (string) $books->links()
            );


    		return response()->json($parameters);
    	}else {

    	/**
    	* SAMPLE
    	* assume $filterOption: genre-Horror
    	* $filter[0] = genre
    	* $filter[1] = Horror
		*/ 
        
    	$filter = preg_split ("/\-/",$filterOption);

		$book = book::where($filter[0],$filter[1]) 
				->where('status','')
				->orderBy('title','ASC')
				->paginate(5);

		$this->_filter = $filter[1];


    	$viewParameter = array(
    			'books' => $book,
    			'filterHeader' => $this->_filter,
    			'filterOption' => 'filterOption',
    			'filterValue' => $filterOption,
    			'searchHeader' => $this->_search,
    			'searchOption' => '',
    			'searchValue' => '',
                'pagination' => (string) $book->links()
    			);
            return response()->json($viewParameter);
        }
        
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
    public function update(Request $request)
    {
    	
        $book = book::find($request->id);

        if( $request->status != 'borrowed' ) {
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
    public function destroy(Request $request)
    {
        $book = book::find($request->id);
        $book->delete();
    }
}

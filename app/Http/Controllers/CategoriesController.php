<?php

namespace App\Http\Controllers;

use App\Models\Jobs\Bookmarks;
use App\Models\Jobs\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    public function index(){
        $categories = Categories::orderBy('cat_order','ASC')->get();
        return view('jobs.categories',compact('categories'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin($id)
    {
        $category = Categories::find($id);
        $jobs = $category->jobs()->paginate(20);
        $title = 'Jobs by ' . $category->name;
        return view('home', compact('jobs', 'category', 'title'));
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:50',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $cat = new Categories();
        $cat->name = $request->name;
        $cat->desc = $request->desc;

        //TODO this code was added
        $cat->parent_id = $request->parent_id;
        $cat->save();
        flash()->success('Category saved!');
        return redirect()->back();
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:50',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $cat = Categories::findOrFail($id);
        $cat->name = $request->name;
        $cat->desc = $request->desc;
        $cat->save();
        flash()->success('Category updated!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = Categories::findOrFail($id);
        $cat->delete();
        flash()->success('Category deleted');
        return redirect()->back();
    }
    /**
     * @param Request $request
     * @return string
     */
    function order(Request $request)
    {
        if ($request->ajax()) {
            $id_ary = explode(",", $request ->sort_order);
            for ($i = 0; $i < count($id_ary); $i++) {
                $q = Categories::find($id_ary[$i]);
                $q->cat_order = $i;
                $q->save();
            }
            return 'success';
        }
        return '';
    }
}

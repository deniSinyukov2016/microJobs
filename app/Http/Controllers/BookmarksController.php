<?php

namespace App\Http\Controllers;

use App\Models\Jobs\Bookmarks;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class BookmarksController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * @param Request $request
     */
    function store(Request $request)
    {
        if ($request->ajax()) {
            $bookmark = Bookmarks::whereJobId($request->job_id)->whereUserId(Auth::user()->id)->first();
            if (count($bookmark) == 0) {
                $bk = new Bookmarks();
                $bk->job_id = $request->job_id;
                $bk->user_id = Auth::user()->id;
                $bk->created_at = date('Y-m-d H:i:s');
                if ($bk->save()) {
                    $status = 'success';
                    $msg = 'Bookmark saved!';
                } else {
                    $status = 'error';
                    $msg = 'Unable to save bookmark';
                }
            }

        } else {
            $status = 'error';
            $msg = 'Request was invalid';
        }
        echo json_encode(['status' => $status, 'message' => $msg]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
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
        $b = Bookmarks::findOrFail($id);
        $b->name = $request->name;
        $b->desc = $request->desc;
        $b->save();
        flash()->success('Bookmark update');
        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    function destroy(Request $request)
    {
        if ($request->ajax()) {
            $bk = Bookmarks::find($request->id);
            if ($bk->delete()) {
                $status = 'success';
                $msg = 'Bookmark removed!';
            } else {
                $status = 'error';
                $msg = 'Unable to remove bookmark';
            }

        } else {
            $status = 'error';
            $msg = 'Request was invalid';
        }
        echo json_encode(['status' => $status, 'message' => $msg]);
    }



    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function userBookmarks(Request $request)
    {
        $bookmarks = Auth::user()->bookmarks;
        $jobs = array();
        foreach ($bookmarks as $bookmark) {
            if ($bookmark->job !== null) //skip trashed
                $jobs[] = $bookmark->job;
        }
        $jobs = self::bJobs($jobs, $request, 20);
        $title = 'My Bookmarks';
        return view('home', compact('jobs', 'bookmarks', 'title'));

    }

    /**
     * @param $jobs
     * @param $request
     * @param $perPage
     * @return LengthAwarePaginator
     */
    function bJobs($jobs, $request, $perPage)
    {
        $page = Input::get('page', 1); // Get the ?page=1 from the url
        $offset = ($page * $perPage) - $perPage;
        return new LengthAwarePaginator(
            array_slice($jobs, $offset, $perPage, true), // Only grab the items we need
            count($jobs), // Total items
            $perPage, // Items per page
            $page, // Current page
            ['path' => $request->url(), 'query' => $request->query()]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Jobs\Skills;
use App\Models\User\UserSkills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SkillsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-job-skills', ['only' => ['index']]);
        $this->middleware('permission:create-job-skills',['only'=>['store']]);
        $this->middleware('permission:read-job-skills',['only'=>['show']]);
        $this->middleware('permission:update-job-skills',['only'=>['update']]);
        $this->middleware('permission:delete-job-skills',['only'=>['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $skills = Skills::paginate(25);
        return view('jobs.skills', compact('skills'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:50|unique:skills',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        Skills::create($request->all());
        flash()->success('Skill added!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (request()->ajax()) {
            $skill = Skills::findOrFail($id);
            echo json_encode($skill->toArray());
        }
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
            'name' => 'required|max:50|unique:skills,name,'.$id,
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $skill = Skills::find($id);
        $skill->fill($request->all());
        $skill->save();
        flash()->success('Skill update!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $skill = Skills::findOrFail($id);
        $skill->delete();
        flash()->success('Skill deleted!');
        return redirect()->back();
    }

    function skillsJson()
    {
        $term = $_GET['q'];
        $skills = Skills::where('name', 'LIKE', "%$term%")
            ->orWhere('desc', 'LIKE', "%$term%")->get();

        $json = array();
        foreach ($skills as $skill) {
            array_push($json, array(
                    'id' => $skill->id,
                    'name' => $skill->name)
            );
        }

        echo json_encode($json);
    }

    function deleteMySkill(Request $request)
    {
        if ($request->ajax()) {
            $skill = UserSkills::whereUserId(Auth::user()->id)->whereSkillId($request->skill_id)->first();
            $skill->delete();
            echo 'success';
        }
    }
}

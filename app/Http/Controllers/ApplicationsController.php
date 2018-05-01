<?php

namespace App\Http\Controllers;

use App\Mail\AdminNewJobAppNotice;
use App\Mail\ApplicationConversationNotice;
use App\Mail\NotifyUserAppStatus;
use App\Models\Jobs\Applications;
use App\Models\Jobs\conversations;
use App\Models\Jobs\Jobs;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class ApplicationsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-job-applications', ['only' => ['show', 'applicationsAdmin']]);
        $this->middleware('permission:delete-application-message', ['only' => ['deleteMessage']]);
        $this->middleware('permission:create-application-message', ['only' => ['postMessage']]);
        $this->middleware('permission:update-job-applications', ['only' => ['changeStatus']]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function show($id)
    {
        $app = Applications::findOrFail($id);
        return view('jobs.applications.show', compact('app'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function applicationsAdmin()
    {
        $applications = Applications::orderBy('created_at', 'DESC')->get();
        return view('applications.admin', compact('applications'));
    }

    /**
     * @param Request $request
     * @return null|void
     */
    function applyJob(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'job_id' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $msg = 'Please try again!';
                $status = 'error';
                echo json_encode(['status' => $status, 'message' => $msg]);
                return;
            }

            $job = Jobs::find($request->job_id);

            $applicant = new Applications();
            $applicant->job_id = $request->job_id;
            $applicant->user_id = Auth::user()->id;
            $applicant->remarks = $request->remarks;
            $applicant->created_at = date('Y-m-d H:i:s');
            $applicant->status = 'pending';
            $applicant->job_price = $job->price;
            $applicant->save();

            //notify admin
            $job = Jobs::find($request->job_id);
            try {
                Mail::to(env('MAIL_FROM_ADDRESS', env('MAIL_FROM_NAME')))->send(new AdminNewJobAppNotice($job, $applicant));
            } catch (Exception $e) {
                echo json_encode(['status' => 'success', 'message' => 'Application has been saved but we had trouble notifying job poster. Please contact them directly.']);
                return null;
            }
            echo json_encode(['status' => 'success', 'message' => 'Application has been sent successfully']);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function appStatus($id)
    {
        if (request()->ajax()) {
            $app = Applications::find($id);
            return view('jobs.application-status', compact('app'));
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function myApplications()
    {
//        $jobs = Auth::user()->appliedJobs()->paginate(20);
//        $title = 'All applied jobs';
        $applications = Auth::user()->applications()->paginate(20);
        return view('applications.my-applications', compact('applications'));
    }

    /**
     * @param Request $request
     */
    function changeStatus(Request $request)
    {
        if ($request->ajax()) {
            $app = Applications::find($request->app_id);
            $app->status = $request->status;
            $app->save();

            $app->notifyOnChangeStatus($app);

            echo json_encode(['status' => 'success', 'message' => 'Status updated']);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function postMessage(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'message' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $user = 'User';

            Conversations::create([
                'message' => $request->message,
                'user_id' => Auth::user()->id,
                'application_id' => $request->app_id
            ]);

            //notify users of a message
            $app = Applications::find($request->app_id);
            $job = Jobs::find($app->job_id);
            //user is posting
            if ((int)Auth::user()->id == (int)$app->user_id) { //notify admin
                try {
                    Mail::to(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))->send(new ApplicationConversationNotice($job, $app, 'admin'));
                } catch (Exception $e) {
                    echo json_encode(['status' => 'success', 'message' => 'Message has been saved but the notification serve did not respond.']);
                    return null;
                }
            } else { //notify user
                $owner = User::find($app->user_id);
                try {
                    Mail::to($owner->email, $owner->name)->send(new ApplicationConversationNotice($job, $app, 'user'));
                } catch (Exception $e) {
                    echo json_encode(['status' => 'success', 'message' => 'Message has been saved but the notification serve did not respond.']);
                    return null;
                }
            }
            echo json_encode(['status' => 'success', 'user' => $user, 'date' => date('d M, y')]);
        }
    }

    /**
     * @param Request $request
     */
    function deleteMessage(Request $request)
    {
        if ($request->ajax()) {
            $m = Conversations::find($request->message_id);
            $m->delete();
            echo 'success';
        }
    }
}

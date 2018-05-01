@extends('layouts.app')
@section('content')
    <h3>
        {{$job->name}}

        <div class="pull-right">
            @permission('update-jobs')
            <a href="/jobs/{{$job->id}}/edit" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> </a>
            @endpermission
            @permission('read-jobs-manager')
            <a href="/jobsAdmin" class="btn btn-danger btn-xs"><i class="fa fa-times"></i> </a>
            @endpermission
        </div>
    </h3>


    <table class="table table-responsive table-striped">
        <tr>
            <td>Difficulty:</td>
            <td>{{$job->difficulty->name}}</td>
            <td>Price:</td>
            <td>{{$job->formattedPrice}}</td>
            <td>Status:</td>
            <td>{!! $job->prettyStatus !!}</td>
        </tr>
        <tr>
            <td>Posted:</td>
            <td>{{$job->created_at}}</td>
            <td>Ends:</td>
            <td>{{$job->end_date}}</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Skills</td>
            <td colspan="5" class="text-left">
                @foreach($job->skills as $skill)
                    <span class="label label-default">{{$skill->name}}</span>
                @endforeach
            </td>
        </tr>

    </table>
    @permission('read-job-applications')
    <h4>Applications</h4>
    <table class="table table-responsive table-striped">
        <thead>
        <tr>
            <th>Date</th>
            <th>User</th>
            <th>Status</th>
            <td></td>
        </tr>
        </thead>
        <tbody>

        @foreach($job->applications()->paginate(20) as $application)
            <tr>
                <td>{{$application->created_at}}</td>
                <td>
                    <a target="_blank" href="/users/{{$application->user_id}}/view#bio" class="view-user"
                       id="{{$application->user_id}}">
                        {{$application->user->name}}
                    </a>
                </td>
                <td>{!!$application->prettyStatus !!}
                    @if($application->status =="approved")
                        <a class="btn btn-default btn-xs"
                           data-toggle="tooltip"
                           title="Open conversation" href="/job/{{$job->id}}/{{$application->id}}/work">
                            <i class="fa fa-folder-open"></i> </a>
                    @endif
                </td>

                <td class="text-right">
                    @if(\Trust::can('update-job-applications'))
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" id="menu1"
                                    data-toggle="dropdown">
                                Change Status
                                <span class="caret"></span></button>

                            <ul class="dropdown-menu change-status" role="menu">
                                <li id="{{$application->id}}" data-status="pending">
                                    <a href="#"><i class="fa fa-refresh"></i> Pending</a></li>
                                <li id="{{$application->id}}" data-status="approved">
                                    <a href="#"><i class="fa fa-check-circle text-success"></i> Approved</a></li>
                                <li id="{{$application->id}}" data-status="denied">
                                    <a href="#"><i class="fa fa-stop-circle text-danger"></i> Denied</a></li>
                                <li id="{{$application->id}}" data-status="closed">
                                    <a href="#"><i class="fa fa-times-circle-o"></i> Closed</a></li>
                                <li id="{{$application->id}}" data-status="cancelled">
                                    <a href="#"><i class="fa fa-ban text-danger"></i> Cancelled</a></li>
                            </ul>

                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Remarks:</td>
                <td colspan="3">
                    {{$application->remarks}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{$job->applications()->paginate(20)->links()}}
    @endpermission
@endsection
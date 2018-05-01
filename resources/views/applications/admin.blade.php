@extends('layouts.app')
@section('content')
    <h2>Applications manager</h2>
    <div id="applications">

        <div class="input-group col-xs-6">
            <input class="search form-control input-sm" placeholder="Search"/>
            <span class="input-group-btn">
            <button class="btn btn-default btn-sm" data-sort="job">
                <i class="fa fa-search"></i>
            </button>
            </span>
        </div>
        <div class="list">

            @foreach($applications as $application)
                <div class="row small" style="border-bottom: solid 1px #ccc;padding:5px 0">
                    <div class="col-xs-2">{{$application->created_at}}</div>
                    <div class="col-xs-5 job">

                        <a data-toggle="tooltip" title="Open conversation"
                           href="/jobs/{{$application->job->id}}">{{$application->job->name}}</a>

                        @if($application->status =="approved")
                            <a class="btn btn-default btn-xs pull-right"
                               data-toggle="tooltip"
                               title="Open conversation"
                               href="/job/{{$application->job->id}}/{{$application->id}}/work">
                                <i class="fa fa-envelope-open-o"></i> </a>
                        @endif
                    </div>
                    <div class="col-xs-1">
                        <a href="/jobs/{{$application->job_id}}" data-toggle="tooltip" title="Manage applications">
                            <i class="fa fa-pencil-square-o"></i></a>
                    </div>
                    <div class="col-xs-3 applicant">
                        <a data-toggle="tooltip" title="View user profile"
                           href="/users/{{$application->user->id}}/view">{{$application->user->name}}</a></div>
                    <div class="col-xs-1 status">
                        {!! $application->prettyStatus !!}
                    </div>
                </div>
            @endforeach
        </div>

        <ul class="pagination"></ul>
    </div>
@endsection
@push('scripts')
<script src="/plugins/listjs/listjs.min.js"></script>
<script>
    var applications = new List('applications', {
        valueNames: ['job', 'applicant', 'status'],
        page: 50,
        pagination: true
    });
</script>
@endpush
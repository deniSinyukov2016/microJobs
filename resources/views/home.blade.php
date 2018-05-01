@extends('layouts.app')

@section('content')
    <h4>
        @if(isset($title))
            {{$title}}
        @else
            All Jobs
        @endif
    </h4>

    @if(isset($category))
        <div class="alert bg-info small">{{$category->name}}</div>
    @endif

    <div class="row">
        @foreach($jobs as $job)
            <div class='col-sm-6 col-xs-12 col-md-6 col-lg-6'>
                <div class="panel panel-default box">
                    <div class="panel-heading">
                        <h4 id="{{$job->id}}">{{$job->name}}</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <span class="label label-{{$job->difficulty->name=="advanced"?'danger':'info'}} level">{{$job->difficulty->name}}</span>
                            </div>
                            <div class="col-xs-2 text-center">
                                <span>{{$job->formattedPrice}}</span>
                            </div>

                            <div class="col-xs-4 text-right">
                                <span class="small">Ends: {{$job->end_date}}</span>
                            </div>
                        </div>
                        <br/>
                        <div class="job-desc" id="{{$job->id}}">
                            {{str_limit(strip_tags($job->desc,200)) }}
                        </div>
                        @foreach($job->skills as $skill)
                            <span class="label label-default">{{$skill->name}}</span>
                        @endforeach
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            @if($job->status =="closed" && !Auth::user()->hasRole('admin'))
                                <div class="col-xs-3 col-xs-offset-4">
                                    <div class="label label-danger">Job is closed</div>
                                </div>
                            @else
                                <div class="col-xs-4">
                                    @if(Auth::check() && count($job->application))
                                        <button id="{{$job->application->id}}"
                                                class="btn btn-success btn-sm job-app-status-btn"><i
                                                    class="fa fa-briefcase"></i> check status
                                        </button>
                                    @else
                                        <button id="{{$job->id}}" class="btn btn-default btn-sm apply-job-btn"><i
                                                    class="fa fa-briefcase"></i> apply
                                        </button>
                                    @endif
                                </div>
                                <div class="col-xs-5 text-center">

                                    @if(Auth::check() && count($job->bookmarks->where('user_id',Auth::user()->id)))
                                        <button id="{{$job->bookmarks->where('user_id',Auth::user()->id)->first()->id}}"
                                                class="btn btn-default btn-sm bookmark-job-remove"><i
                                                    class="fa fa-heart text-danger"></i>
                                        </button>
                                    @else
                                        <button id="{{$job->id}}" class="btn btn-default btn-sm bookmark-job"><i
                                                    class="fa fa-heart"></i>
                                        </button>
                                    @endif
                                    @role('admin')
                                    <a href="/jobs/{{$job->id}}" class="btn btn-default btn-xs"><i
                                                class="fa fa-pencil"></i>
                                    </a>
                                    <a href="#" class="btn btn-warning btn-xs" data-toggle="tooltip"
                                       title="Open/Close Job"
                                       onclick="updateJobStatus('{{$job->id}}','{{$job->status=='open'?'closed':'open'}}')">
                                        <i class="fa fa-{{$job->status=='open'?'close':'eye'}}"></i>
                                    </a>
                                    <a href="#" id="{{$job->id}}" class="btn btn-danger btn-xs delete delete-job"><i
                                                class="fa fa-trash"></i></a>

                                    @endrole

                                </div>
                                <div class="col-xs-3 text-right">
                                    <button id="{{$job->id}}" data-title="{{$job->name}}"
                                            class="btn btn-default btn-sm share-job-btn"><i
                                                class="fa fa-share"></i> share
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-sm-12">{{$jobs->links()}}</div>
    </div>
@endsection
@push('modals')
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<div class="modal fade" id="viewJobModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="title"></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-4">Difficulty:<span id="difficulty"></span></div>
                    <div class="col-xs-4">Price: <span id="price"></span></div>
                    <div class="col-xs-4">Ends: <span id="end-date"></span></div>
                </div>
                <hr/>

                <span id="desc"></span>
                <h5><strong>Skills</strong></h5>
                <span id="skills"></span>
                <h5><strong>Category</strong></h5>
                <span id="cats"></span>
            </div>
            <div class="modal-footer">
                <button id="" class="btn btn-default btn-sm bookmark-modal-btn"><i class="fa fa-heart"></i></button>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="applyJobModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::open(['url'=>'','class'=>'apply-job-form']) !!}
            <div class="modal-body">
                {!! Form::hidden('job_id') !!}
                {!! Form::textarea('remarks',null,['class'=>'form-control','rows'=>3,'placeholder'=>'Enter an optional notes']) !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button class="btn btn-primary btn-sm apply-job-form-btn">Send</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="shareJobModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Share <span></span> Job</h4>
            </div>

            {!! Form::open(['url'=>'','class'=>'share-job-form']) !!}
            <div class="modal-body">
                {!! Form::hidden('job_id') !!}
                {!! Form::input('email','email',null,['required'=>'required','class'=>'form-control','placeholder'=>'Enter your friend\'s email']) !!}
                <br/>
                {!! Form::textarea('message',null,['class'=>'form-control','rows'=>3,'placeholder'=>'Enter an optional message']) !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button class="btn btn-primary btn-sm">Send</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endpush

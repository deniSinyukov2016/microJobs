@extends('layouts.app')
@section('content')
    <div class="text-right">

        @if(isset($job))
            <a href="/jobs/create" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> </a>
        @endif
        <a href="/jobsAdmin" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> </a>
    </div>

    @if(isset($job))
        <h2><i class="fa fa-pencil"></i> {{$job->name}}</h2>
        {!! Form::model($job,['url'=>route('jobs.update',$job->id),'method'=>'patch']) !!}
    @else
        <h2><i class="fa fa-plus"></i> New Job</h2>
        {!! Form::open(['url'=>'/jobs']) !!}
    @endif
    <div class="row">
        <div class="col-sm-8">
            <label>Name</label>
            {!! Form::text('name',null,['required'=>'required','class'=>'form-control']) !!}
        </div>
        <div class="col-sm-4">
            <label>Status</label>
            {!! Form::select('status',['open'=>'Open','closed'=>'Closed'],null,['class'=>'form-control']) !!}
        </div>
    </div>
    <label>Description</label>
    {!! Form::textarea('desc',null,['class'=>'editor1']) !!}
    <label>Instructions</label>
    <span class="label label-warning">Only visible to applicant after approval</span>
    {!! Form::textarea('instructions',null,['class'=>'editor2','id'=>'editor2']) !!}
    <br/>
    <div class="row">
        <div class="col-md-4">
            <label>Price</label>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                {!! Form::input('input','price',isset($job)?str_replace('$','',$job->formattedPrice):null,['class'=>'form-control']) !!}
            </div>
        </div>

        <div class="col-md-4">
            <label>Difficulty level</label>
            {!! Form::select('difficulty_level_id',$difficultyLevels,null,['class'=>'form-control']) !!}
        </div>

        <div class="col-md-4">
            <label>End date</label>
            {!! Form::input('date','end_date',isset($job)?date('Y-m-d',strtotime($job->end_date)):null,['required'=>'required','class'=>'form-control']) !!}
        </div>
    </div>

    <label>Skills:</label>
    <div class="row">
        @foreach(\App\Models\Jobs\Skills::get() as $skill)
            <div class="col-sm-3">
                {!! Form::checkbox('skills[]',$skill->id) !!}{{ucwords($skill->name)}}
            </div>
        @endforeach
    </div>

    <br/>
    <label>Categories</label>
    <div class="row">
        @foreach($categories as $cat)
            <div class="col-sm-3">
                {!! Form::checkbox('categories[]',$cat->id,null,['style'=>'']) !!} {{$cat->name}}
            </div>
        @endforeach
    </div>
    <br/>
    <button class="btn btn-default btn-md">Submit</button>
    {!! Form::close() !!}
    <div class="clearfix">
        <hr/>
    </div>
@endsection
@include('partials.summer',['editor'=>'.editor1'])
@include('partials.tinymce',['editor'=>'.editor2'])

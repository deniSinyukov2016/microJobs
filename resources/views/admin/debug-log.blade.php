@extends('layouts.app')

@section('content')
    <h2>Debug logs</h2>
    <div class="row">
        @include('admin.settings-nav')
        <div class="col-sm-9">
            {!! Form::open(['url'=>route('empty-debug')]) !!}
            <textarea name="logContent" class="form-control" rows="20">{!! $logContent !!}</textarea>
            <br/>
            <button class="btn btn-danger">Empty log</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

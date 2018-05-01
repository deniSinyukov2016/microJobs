@extends('layouts.app')

@section('content')
    <h2>System logs</h2>
    <div class="row">
        @include('admin.settings-nav')
        <div class="col-sm-9">
            {!! Form::open(['url'=>route('empty-log')]) !!}
            <textarea name="envContent" class="form-control" rows="20">{!! $logContent !!}</textarea>
            <br/>
            <button class="btn btn-danger">Empty log</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@extends('layouts.app')
@section('content')
    <h2>Settings</h2>
    <div class="row">
        @include('admin.settings-nav')
        <div class="col-sm-9">
                <div class="callout callout-info">
                    <div class="title"><h4>Notice!</h4></div>
                    All site configurations are managed in <code>.evn</code> file located in the root
                    of your application.`
                    <div class="text-danger">
                        Change these settings only if you know what you are doing!
                    </div>
                    {!! Form::open(['url'=>'admin/settings/backup']) !!}
                    <button class="btn btn-warning"><i class="fa fa-database"></i> Backup First!</button>
                    {!! Form::close() !!}
                </div>

                {!! Form::open() !!}
                <textarea name="envContent" class="form-control" rows="20">{!! $envContent !!}</textarea>
                <br/>
                <button class="btn btn-default"><i class="fa fa-save"></i> Update</button>
                {!! Form::close() !!}
            </div>
        </div>
@endsection

@extends('layouts.app')
@section('content')
    <div class="row">
        @include('admin.settings-nav')
        <div class="col-md-6">
            <h2>Users</h2>
            <table class="table table-striped table-responsive table-full-width" id="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered</th>
                    <th data-orderable="false"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)

                    <tr>

                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{date('d M y',strtotime($user->created_at))}}</td>
                        <td>
                            <a data-toggle="tooltip" title="edit" href="/users/{{$user->id}}/view"
                               class="table-action-btn"><i
                                        class="fa fa-pencil-square fa-2x"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
           {!! $users->links() !!}
        </div>
        <div class="col-md-3">
            <h2>Register User</h2>
            {!! Form::open(['url'=>'/users/register','id'=>'register-form']) !!}

            <div class="row">

                <div class="col-md-12">
                    <label>Name:</label>
                    {{Form::text('name',null,['required'=>'required','class'=>'form-control'])}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>Email:</label>
                    {{Form::input('email','email',null,['required'=>'required','class'=>'form-control'])}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>Password</label>
                    {!! Form::input('password','password',null,['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <label>Confirm password</label>
                    {!! Form::input('password','password_confirmation',null,['class'=>'form-control']) !!}

                </div>
            </div>
            <br/>
            <button class="btn btn-primary btn-block text-uppercase waves-effect waves-light" type="submit">
                Submit
            </button>

            {!! Form::close() !!}
        </div>
    </div>

@endsection
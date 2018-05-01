@extends('layouts.app')

@section('content')
    <h2>Roles</h2>
    <div class="row">
        @include('admin.settings-nav')
        <div class="col-sm-9">
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                When you create new modules, add them here and assign permissions. For example if module is
                <code>users</code>, then permissions are generated as
                <code>create-users</code>
                <code>read-users</code>
                <code>update-users</code>
                <code>delete-users</code>.
                In your module code, you can define access using
                <div class="clearfix"><br/></div>
                <div class="row">
                    <div class="col-xs-5">
                        <code>
                            if(\Trust::can('create-users')<br/>
                            &nbsp; &nbsp; &nbsp;---your code here---<br/>
                            &nbsp;endif
                        </code>
                    </div>
                    <div class="col-xs-2">
                        OR
                    </div>
                    <div class="col-xs-5">
                        <code>
                            &commat;if(permission('create-users')<br/>
                            &nbsp; &nbsp; &nbsp;---your code here---<br/>
                            &nbsp;&commat;endif
                        </code>
                    </div>
                </div>
                <div class="clearfix"><br/></div>
                <p>
                    Default modules
                    <code>users</code>
                    <code>profile</code>
                    <code>jobs</code>
                    <code>job-categories</code>
                    <code>jobs-manager</code>
                    <code>job-applications</code>
                    <code>application-message</code>
                    <code>job-skills</code>
                    <code>payouts</code>
                    <code>logs</code>
                </p>
            </div>

            <div class="row">
                <div class="col-xs-4 col-sm-3">
                    <div class="">
                        <strong>Roles</strong>
                        <a href="#" data-toggle="tooltip" title="Add a role" class="pull-right create-role-btn"><i
                                    class="fa fa-plus-square"></i></a>
                    </div>
                    <div id="roles">
                        <input class="search form-control input-sm" placeholder="Search"/><br/>
                        <i>double click to edit</i>
                        <ul class="list nav nav-pills nav-stacked">
                            @foreach($roles as $role)
                                <li id="{{$role->id}}" data-toggle="tooltip" title="{{$role->desc}}">
                                    <a href="#" class="role" id="{{$role->id}}">
                                        {{ucwords($role->display_name)}}
                                        <span class="pull-right"><i class="fa fa-chevron-right"
                                                                    style="opacity: 0.2;"></i> </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <ul class="pagination"></ul>
                    </div>
                </div>
                <div class="col-xs-4 col-sm-3">
                    <div class="">
                        <strong>Modules</strong>
                        <a href="#" data-toggle="tooltip" title="Register a module"
                           class="pull-right register-module-btn"><i
                                    class="fa fa-plus-square"></i></a>
                    </div>
                    <div id="modules">

                    </div>

                </div>
                <div class="col-xs-4 col-sm-3">
                    <strong>Permissions</strong><br/>
                    <div id="permissions">

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script src="/plugins/listjs/listjs.min.js"></script>
@endpush
@push('modals')

<div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-circle"></i> New Role</h4>
            </div>
            {!! Form::open(['url'=>'/roles']) !!}
            <div class="modal-body">
                <label>Name <i class="small">(no spaces or special characters)</i></label>
                {!! Form::text('name',null,['class'=>'form-control']) !!}
                <label>Display name</label>
                {!! Form::text('display_name',null,['class'=>'form-control']) !!}
                <label>Description</label>
                {!! Form::textarea('description',null,['rows'=>2,'class'=>'form-control']) !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button class="btn btn-primary">Submit</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


<div class="modal fade" id="modulesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">New Module</h4>
            </div>
            {!! Form::open(['url'=>route('modules.store'),'method'=>'post']) !!}
            <div class="modal-body">
                <label>Name<i class="small">(no spaces or special characters)</i></label>
                {!! Form::text('name',null,['required'=>'required','class'=>'form-control']) !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button class="btn btn-primary">Submit</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endpush
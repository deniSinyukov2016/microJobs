@extends('layouts.app')
@section('content')
    <h2>Skills</h2>
    <div class="row">
        <div class="col-md-8">
            <table class="table table-responsive table-striped">
                <tr>
                    <th>Skill</th>
                    <th>Desc</th>
                    <th></th>
                </tr>
                @foreach($skills as $skill)
                    <tr>
                        <td>{{$skill->name}}</td>
                        <td>{{$skill->desc}}</td>
                        <td>
                            <a href="#" class="btn btn-info btn-xs edit-skill" id="{{$skill->id}}">
                                <i class="fa fa-pencil"></i> </a>
                            <a href="#" class="btn btn-danger btn-xs delete-skill" id="{{$skill->id}}">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
            {!! $skills->links() !!}
        </div>
        <div class="col-md-4">
            <strong>Add a skill</strong>
            {!! Form::open(['url'=>'skills','method'=>'post']) !!}

            {{ Form::text('name',null,['class'=>'form-control','id'=>'','placeholder'=>'Name']) }}
            <br/>
            {!! Form::textarea('desc',null,['class'=>'form-control','rows'=>3,'placeholder'=>'Description']) !!}
            <br/>
            <button class="btn btn-default">Submit</button>
            {!! Form::close() !!}
        </div>
    </div>

@endsection
@push('scripts')
<div class="modal fade" id="editSkillModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['method'=>'patch']) !!}
            <div class="modal-body">
                {{ Form::text('name',null,['class'=>'form-control','id'=>'','placeholder'=>'Name']) }}
                <br/>
                {!! Form::textarea('desc',null,['class'=>'form-control','rows'=>3,'placeholder'=>'Description']) !!}
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
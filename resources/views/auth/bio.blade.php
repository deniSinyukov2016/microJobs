{!! Form::open(['url'=>'account/bio','method'=>'post']) !!}

<label>Tell us about yourself</label>
{!! Form::textarea('bio',$user->bio,['class'=>'form-control','rows'=>3,'required'=>'required']) !!}

<label>Skills</label>
{!! Form::text('skills',null,['id'=>'skills','class'=>'form-control']) !!}
@foreach($user->skills as $skill)
    <span class="label label-default">{{$skill->name}} &nbsp; <i id="{{$skill->id}}" class="fa fa-times delete-my-skill" style="cursor:pointer;"></i> </span>
@endforeach
<br/>
<button class="btn btn-default">Update</button>
{!! Form::close() !!}

@include('partials.tokeninput',['elem'=>'skills','path'=>'/skills-json'])
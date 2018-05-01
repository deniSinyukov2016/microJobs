@extends('layouts.app')
@section('content')
    <h2>@lang('My Account')</h2>
    <div class="row">
        <div class="col-md-4">
            <ul class="nav nav-tabs nav-pills nav-stacked" role="tablist">
                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab"
                                                          data-toggle="tab">@lang('Home')</a></li>
                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab"
                                           data-toggle="tab">@lang('Profile')</a></li>
                <li role="presentation">
                    <a href="#bio" aria-controls="bio" role="tab" data-toggle="tab">@lang('Bio')</a>
                </li>
            </ul>
        </div>
        <div class="col-md-8">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home">

                    <div class="row">
                        <div class="col-sm-6">
                            <h4>{{$user->name}}</h4>
                            <p>{{$user->email}}</p>
                            Member since: {{$user->registeredOn}}
                        </div>
                        <div class="col-sm-4">
                            <div style="padding:0;margin:0;width:40px;" class="pull-left h1 text-center">
                                {{count($user->applications)}}
                            </div>
                            <div style="padding-top:10px;">Applications</div>
                            <div class="clearfix"></div>

                            <div style="padding:0;margin:0;width:40px;" class="pull-left h1 text-center">
                                {{count($user->applications->where('status','approved'))}}
                            </div>
                            <div style="padding-top:10px;">Approved</div>

                            <div class="clearfix"></div>

                            <div style="padding:0;margin:0;width:40px;" class="pull-left h1 text-center">
                                {{count($user->applications->where('status','pending'))}}
                            </div>
                            <div style="padding-top:10px;">Pending</div>
                        </div>
                    </div>
                    <hr/>

                </div>
                <div role="tabpanel" class="tab-pane fade" id="profile">
                    {!! Form::model($user,['url'=>'/account/profile', 'method' => 'patch']) !!}
                    <label>@lang('Name')</label>
                    {!! Form::text('name',null,['class'=>'form-control','required'=>'required']) !!}
                    <label>@lang('Email')</label>
                    {!! Form::input('email','email',null,['class'=>'form-control','required'=>'required']) !!}
                    <div class="alert alert-info">
                        @lang('payments.paypal-note')
                    </div>

                    <hr/>
                    <h4>Stripe</h4>
                    <div class="alert alert-info">
                        @lang('payments.stripe-note')
                    </div>
                    <label>@lang('payments.stripe-publishable-key')</label>
                    {!! Form::text('stripe_public_key',null,['class'=>'form-control']) !!}
                    <label>@lang('payments.stripe-secret-key')
                        @if($user->stripe_secret_key !=='')
                            <i class="label label-success">on file</i>
                        @endif
                    </label>
                    {!! Form::input('password','stripe_secret_key',null,['class'=>'form-control']) !!}


                    <hr/>
                    <h4>@lang("Password")</h4>
                    <div class="callout callout-warning">
                        <label>@lang('auth.new-password')</label>
                        {!! Form::input('password','password',null,['class'=>'form-control']) !!}
                        <label>@lang('auth.confirm-password')</label>
                        {!! Form::input('password','password_confirmation',null,['class'=>'form-control']) !!}
                    </div>
                    <br/>
                    <button class="btn btn-default"><i class="fa fa-save"></i> @lang('Update')</button>
                    {!! Form::close() !!}
                </div>
                <div role="tabpanel" class="tab-pane fade" id="bio">
                    @include('auth.bio')
                </div>
            </div>
        </div>

    </div>

@endsection
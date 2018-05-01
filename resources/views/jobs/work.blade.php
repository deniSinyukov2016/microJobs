@extends('layouts.app')
@section('content')
    <h3>@lang("Conversation for") {{$job->name}}</h3>
    <div class="row">
        <div class="col-sm-4">
            <table class="small table table-striped">
                <tr>
                    <td class="text-right">@lang('Deadline'):</td>
                    <td>{{$application->deadline}} </td>
                </tr>
                <tr>
                    <td class="text-right">@lang('Offer price'):</td>
                    <td> {{$application->formattedPrice}} </td>
                </tr>
                <tr>
                    <td class="text-right">@lang('Status'):</td>
                    <td>{!! $application->prettyStatus !!}</td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6">
                    @permission('update-job-applications')
                    @if(count($payout)==0)
                        <button class="btn btn-primary" type="button" id="menu1" data-toggle="dropdown">
                            @lang('Change Status')
                            <span class="caret"></span></button>

                        <ul class="dropdown-menu change-status" role="menu">
                            <li id="{{$application->id}}" data-status="pending">
                                <a href="#"><i class="fa fa-refresh"></i> @lang('Pending')</a></li>
                            <li id="{{$application->id}}" data-status="approved">
                                <a href="#"><i class="fa fa-check-circle text-success"></i> @lang('Approved')</a></li>
                            <li id="{{$application->id}}" data-status="denied">
                                <a href="#"><i class="fa fa-stop-circle text-danger"></i> @lang('Denied')</a></li>
                            <li id="{{$application->id}}" data-status="closed">
                                <a href="#"><i class="fa fa-times-circle-o"></i> @lang('Closed')</a></li>
                            <li id="{{$application->id}}" data-status="cancelled">
                                <a href="#"><i class="fa fa-ban text-danger"></i> @lang('Cancelled')</a></li>
                        </ul>
                    @endif
                    @endpermission
                </div>
                <div class="col-sm-6">
                    @permission('create-payouts')
                    @if(count($payout)>0)
                        <button class="btn btn-success disabled btn-block">
                            <i class="fa fa-check"></i> @lang('paid')
                        </button>
                    @elseif($application->status =="approved")
                        {{Form::open(['url'=>'paypal/pay'])}}
                        {{Form::hidden('job_id',$job->id)}}
                        {!! Form::hidden('app_id',$application->id) !!}
                        <button class="btn btn-default"><i class="fa fa-paypal text-primary"></i>
                            @lang("Complete and pay")
                        </button>
                        {!! Form::close() !!}

                        <p class="small">
                            @lang('pay to',['amount'=>$application->formattedPrice, 'user'=>$application->user->email])

                            @if(env('PAYPAL_MODE')=='sandbox')
                                <span class="label label-danger">@lang("sandbox")</span>
                            @endif
                        </p>
                    @endif
                    @endpermission

                    @if(\Trust::can('create-payouts')
                    && Auth::user()->id !==$application->user_id
                    && $application->user->stripe_public_key !==null
                    && count($payout)==0)
                        {!! Form::open(['route'=>'stripe-charge','id'=>'payment-form']) !!}
                        <button type="button" class="btn btn-primary" id="stripe-payout">
                            <i class="fa fa-cc-stripe"></i>
                            @lang('Pay with',['method'=>'Stripe'])
                        </button>
                        {!! Form::close() !!}
                    @endif

                    @if(Auth::user()->id ==$application->user_id && Auth::user()->stripe_public_key ==null)
                        <a href="/account/#profile" class="btn btn-info">
                            <i class="fa fa-cc-stripe"></i>
                            @lang("Receive payments through",['method'=>'Stripe'])
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            {{$application->admin_remarks}}
        </div>
    </div>

    <h4>@lang("Special instructions")</h4>
    {!! str_replace('../../storage','/storage',$job->instructions) !!}

    @foreach($application->messages()->paginate(50) as $message)
        <div class="comment alert  alert-{{$message->user_id == $application->user_id?'default':'warning'}}"
             style="padding:10px;margin-bottom:5px;border-bottom:solid 1px #ccc;">

            @permission('delete-application-message')
            <a href="#" id="{{$message->id}}" class="pull-right del-comment">
                <i class="fa fa-trash-o text-danger"></i>
            </a>
            @endpermission
            <p class="small">@lang("Posted by",["user"=>$message->user_id == $application->user_id?'User':'Admin'])
                @lang("on date",['date'=>date('d M, y',strtotime($message->created_at))])</p>
            {!! $message->message!!}
        </div>
    @endforeach

    <div class="comment-end"></div>

    @if($application->status !=='approved')
        <div class="alert alert-danger">
            @lang('Conversation is currently closed for this task')
        </div>
    @else
        <div style="border:solid 1px #ccc;padding:5px;">
            <strong>@lang("Post a new message")</strong>
            {{Form::textarea('comments',null,['class'=>'form-control new-comment-form editor','rows'=>'3','required'=>'required'])}}

            <div class="btn-group comment-btns">
                <button id="{{$application->id}}" type="button" class="btn btn-primary add-comment-btn">
                    @lang("Post")
                </button>
                <button type="button" class="btn btn-danger cancel-comment-btn">@lang("Cancel")</button>
            </div>
        </div>
    @endif

    {{$application->messages()->paginate(50)->links()}}

@endsection
@include('partials.summer',['editor'=>'.editor'])
@push('scripts')
<script src="https://checkout.stripe.com/checkout.js"></script>

<script>
    $('document').ready(function () {

        var handler = StripeCheckout.configure({
            key: '{{$application->user->stripe_public_key}}',
            image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
            locale: 'auto',
            token: function (token) {
                var form = document.getElementById('payment-form');
                // You can access the token ID with `token.id`.
                // Get the token ID to your server-side code for use.
                $('<input>').attr({
                    type: 'hidden',
                    name: 'stripeToken',
                    value: token.id
                }).appendTo('form');
                $('<input>').attr({
                    type: 'hidden',
                    name: 'application_id',
                    value: '{{$application->id}}'
                }).appendTo(form);

                form.submit();
            }
        });
        document.getElementById('stripe-payout').addEventListener('click', function (e) {
            handler.open({
                name: '{{env('APP_NAME')}}',
                description: '{{$application->job->name}}',
                zipCode: false,
                amount: '{{bcmul($application->job_price,100)}}',
                email: '{{Auth::user()->email}}'
            });
            e.preventDefault();
        });

        // Close Checkout on page navigation:
        window.addEventListener('popstate', function () {
            handler.close();
        });
    });
</script>
@endpush
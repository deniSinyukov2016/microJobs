@extends('layouts.app')
@section('content')
    <table class="table table-striped small">
        <thead>
        <tr>
            <th>Date</th>
            <th>Txn ID</th>
            <th>Application</th>
            <th>Item</th>
            <th>Item #</th>
            <th>Amount</th>
            <th>Currency</th>
            <th>Payee</th>
            <th>Payee Email</th>
            <th>Method</th>
        </tr>
        </thead>
        <tbody>
        @foreach($payouts as $payout)
            <tr>
                <td>{{$payout->formattedDate}}</td>
                <td>{{$payout->txn_id}}</td>
                <td>
                    <a href="/job/{{$payout->application->job_id}}/{{$payout->application_id}}/work">
                        {{$payout->application_id}}</a>
                </td>
                <td>{{$payout->item_name}}</td>
                <td>{{$payout->item_number}}</td>
                <td>{{$payout->amount}}</td>
                <td>{{$payout->currency}}</td>
                <td><a href="/users/{{$payout->user->id}}/view">{{$payout->user->name}}</a></td>
                <td>{{$payout->payment_email}}</td>
                <td>{{$payout->pay_method}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
@extends('layouts.app')
@section('content')
    <h2>Jobs Manager</h2>
    <div class="text-right">
        <a href="/jobs/create" class="btn btn-default btn-xs"><i class="fa fa-plus-circle"></i> Post new job</a>
    </div>
    <table class="table table-responsive table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Posted</th>
            <th>Ends</th>
            <th>Price</th>
            <th>Difficulty</th>
            <th>Applied</th>
            <th>B'marks</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($jobs as $job)
            <tr class="small">
                <td><a href="{{route('jobs.show',$job->id)}}">{{$job->name}}</a></td>
                <td>{{$job->created_at}}</td>
                <td>{{$job->end_date}}</td>
                <td>{{$job->formattedPrice}}</td>
                <td>{{$job->difficulty->name}}</td>
                <td><a href="/jobs/{{$job->id}}">{{count($job->applications)}}</a></td>
                <td>{{count($job->bookmarks)}}</td>
                <td>
                    <a href="/jobs/{{$job->id}}/edit"><i class="fa fa-pencil btn btn-xs btn-default edit-job"></i></a>
                    <i class="fa fa-trash btn btn-xs btn-danger delete-job" id="{{$job->id}}"></i>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{$jobs->links()}}
@endsection

@push('scripts')
<script>
    $('document').ready(function () {
        $('.delete-job').click(function () {
            if (!confirm('Are you sure?\n\nThis will also delete bookmarks and applications.'))
                return false;
            var id = $(this).attr('id');
            $.ajax({
                url: '/jobs/' + id,
                data: {_token: _token, id: id}, //$('form').serialize(),
                type: 'DELETE',
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        notice(data.message, data.status);
                        window.location.reload();
                    } else {
                        notice(data.message, data.status);
                    }
                },
                error: function (error) {
                    notice('Unable to process request.', 'error');
                }

            });
        });
    });
</script>
@endpush
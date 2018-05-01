@component('mail::message')
###I thought you might like to check this job out
{{$job->message}}
<hr style="border:dotted 1px #ccc;margin:10px 0;"/>
##{{$job->name}}

{!! $job->desc !!}

**Difficulty:** **{{$job->difficulty->name}}**

**Ends:** **{{$job->end_date}}**


@component('mail::button', ['url' => url()->to('/?job='.$job->id)])
View job
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

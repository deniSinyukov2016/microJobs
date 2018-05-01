@component('mail::message')

{!! $message !!}


@component('mail::button', ['url' => url()->to('job/'.$job->id.'/'.$application->id.'/work')])
Go to application
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

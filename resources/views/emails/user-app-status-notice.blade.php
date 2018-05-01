@component('mail::message')
###You application has been reviewed!

<p>Your application for {{$job->name}} has been reviewed and addressed.</p>
<p>Please login to your account to see the update and any further instructions.</p>


*Job:* {{$job->name}}<br/>
*End date:* {{$job->end_date}}


Login to review applications
@component('mail::button', ['url' => url()->to('/applications')])
    View applications
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

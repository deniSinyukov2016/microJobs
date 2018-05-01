@component('mail::message')
# A user has applied for a position

*Name:*  {{Auth::user()->name}} <br/>
*Email:* {{Auth::user()->email}}
<br/>

*Job:* {{$job->name}}<br/>
*End date:* {{$job->end_date}}


<br/>
###Remarks by applicant:###
{{$applicant->remarks}}


Login to review applications
@component('mail::button', ['url' => url()->to('/jobsAdmin')])
View applications
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

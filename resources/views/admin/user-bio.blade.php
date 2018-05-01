<table class="table table-responsive">
    <tr>
        <td>Name:</td>
        <td>{{$user->name}}</td>
        <td>Email:</td>
        <td>{{$user->email}}</td>
        <td>Registered:</td>
        <td>{{$user->created_at}}</td>
    </tr>
    <tr>
        <td>Skills:</td>
        <td colspan="5">
            {{$user->prettySkills}}
        </td>
    </tr>
    <tr>
        <td>Approved jobs:</td>
        <td>{{count($user->applications()->where('status','approved'))}}</td>
    </tr>
</table>
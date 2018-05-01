<script>
    function notice(errorNote, type) {
        if (type === "error") {
            type = 'danger';
        }
        $.notify({
            icon: 'ti-check',
            message: errorNote

        }, {
            type: type,
            timer: 2000
        });
    }
</script>

@if(isset($errors) && $errors->any())
    @foreach($errors->all() as $error)
        <script>
            notice('<?php echo $error; ?>', 'danger');
        </script>
    @endforeach
@endif
@if(session()->has('message'))
    <script>
        notice('{{session()->get('message')}}', '{{session()->get('notice-type')}}');
    </script>
@endif

@foreach (session('flash_notification', collect())->toArray() as $message)
    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        <script type="text/javascript">
            notice('{{$message['message'] }}', '{{$message['level'] }}')
        </script>
    @endif
@endforeach

@if(session()->has('message'))
    <script>
        notice('{{session()->get('message')}}', '{{session()->get('notice-type')}}');
    </script>
@endif

{{ session()->forget('flash_notification') }}
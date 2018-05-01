
@push('styles')
<link rel="stylesheet" type="text/css" href="/plugins/tokeninput/css/token-input.css"/>
@endpush
@push('scripts')
<script type="text/javascript" src="/plugins/tokeninput/js/jquery.tokeninput.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $("#{{$elem}}").tokenInput("{{$path}}",{
            preventDuplicates: true,
            minChars: 2,
            tokenLimit: 20
        });
    });
</script>

@endpush
@push('styles')
<link rel="stylesheet" href="/plugins/summernote/summernote.css">
<style>
    .note-group-select-from-files {
        display: none;
    }
</style>
@endpush
@push('scripts')
<script src="/plugins/summernote/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('{{$editor}}').summernote({
            toolbar: [
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture','video', 'hr']]
            ]
        });
    });
</script>
@endpush
@push('scripts')
<script src="/plugins/tinymce/tinymce.min.js"></script>

<script type="text/javascript">
    tinymce.init({
        selector: '{{$editor}}',
        height: 400,
        theme: 'modern',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools',
            'autoresize'
        ],
        toolbar1: 'insertfile undo redo | styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media',
        image_advtab: true,
        content_css: [
            '/plugins/tinymce/codepen.min.css'
        ]
    });


</script>
@endpush
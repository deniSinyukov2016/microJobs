@extends('layouts.app')
@section('content')
    <h2>Job categories</h2>

    <div class="row">
        <div class="col-md-10">
            <div class="alert alert-info">
                Drag to order
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        {!! Form::open(['url'=>'categories']) !!}
                        <div class="input-group">

                            {{Form::text('name',null,['class'=>'form-control','placeholder'=>'Enter new category name'])}}
                            {{--//TODO this code was added --}}
                            <select name="parent_id" class="form-control">
                                <option value="">Parent category</option>
                                @foreach(\App\Models\Jobs\Categories::query()->get() as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                            <span class="input-group-btn">
                                <button class="btn btn-default">Submit</button>
                            </span>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody  class="sortable-rows">
                        @foreach($categories as $category)
                            <tr class="sort-row" id="{{$category->id}}">
                                <td class="col-xs-4"><a href="#">{{$category->name}} </a></td>
                                <td class="col-xs-5">{{$category->desc}}</td>
                                <td class="col-xs-3">
                                    <a href="#" class="btn btn-default btn-xs edit-cat-btn"
                                       data-desc="{{$category->desc}}" data-name="{{$category->name}}"
                                       id="{{$category->id}}"><i
                                                class="fa fa-edit"></i></a>
                                    <a href="/categories/" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="/js/jquery-ui.min.js"></script>
<script>
    $('document').ready(function () {
        $('.edit-cat-btn').click(function () {
            var modal = $('#catModal');
            var id = $(this).attr('id');
            var name = $(this).attr('data-name');
            var desc = $(this).attr('data-desc');
            modal.find('input[name=name]').val(name);
            modal.find('textarea[name=desc]').val(desc);
            modal.find('form').attr('action', '/categories/' + id);
            modal.modal('show');
        });


    })
    $(function () {
        $(".sortable-rows").sortable({
            placeholder: "ui-state-highlight",
            update: function (event, ui) {
                updateDisplayOrder();
            }
        });
    });
    // function to save display sort order
    function updateDisplayOrder() {
        var selectedLanguage = [];
        $('.sortable-rows .sort-row').each(function () {
            selectedLanguage.push($(this).attr("id"));
        });
        var dataString = 'sort_order=' + selectedLanguage + '&_token={{csrf_token()}}';
        $.ajax({
            type: "POST",
            url: "/order-categories",
            data: dataString,
            cache: false,
            success: function (data) {
            }
        });
    }
</script>
@endpush

@push('modals')

<div class="modal fade" id="catModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil"></i> Edit Category</h4>
            </div>
            {!! Form::open(['url'=>'categories','class'=>'edit-cat-form','method'=>'patch']) !!}
            <div class="modal-body">
                <label>Name</label>
                {{Form::text('name',null,['class'=>'form-control','placeholder'=>'Enter new category name'])}}

                <label>Description</label>
                {{Form::textarea('desc',null,['class'=>'form-control','rows'=>3])}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button class="btn btn-primary">Submit</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endpush
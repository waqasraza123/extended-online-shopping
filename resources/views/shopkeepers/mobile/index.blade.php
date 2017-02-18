@extends('layouts.app')
@section('head')
    <link href="/theme/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
    <link href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel="stylesheet" />

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('partials.error-messages.error')
            @include('partials.error-messages.success')
            <div class="panel panel-default">
                <div class="panel-heading">Mobiles
                    <a style="float: right;" href="{{ url('/products/mobile/create') }}" class="btn btn-primary btn-sm" title="Add New Mobile"><span class="glyphicon glyphicon-plus" aria-hidden="true"/> Add New</a>
                </div>
                <div class="panel-body">
                    <div class="table-responsive" style="padding-top: 10px">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Brand</th>
                                    <th>Colors</th>
                                    <th>Storage(GB)</th>
                                    <th>Stock</th>
                                    <th>Price(RS)</th>
                                    <th>Discount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($mobile as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->brand->name }}</td>
                                    <td id="colors_text_box">
                                        @foreach($item->colors as $color)
                                            <span>
                                                {{ trim($color->color)}}
                                            </span>
                                        @endforeach
                                    </td>

                                    <td>
                                        @foreach($item->storages as $s)
                                            {{$s->storage}}
                                            @if(!$loop->last)
                                                {{'|'}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{$item->stock}}</td>
                                    <td>{{number_format($item->current_price)}}</td>
                                    <td>{{$item->discount}}</td>

                                    <td>
                                        <a href="{{ url('/products/mobile/' . $item->id) }}" class="btn btn-success btn-xs" title="View Mobile"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"/></a>
                                        <a href="{{ url('/products/mobile/' . $item->id. '/edit') }}" class="btn btn-primary btn-xs" title="Edit Mobile"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
                                        {!! Form::open([
                                            'method'=>'DELETE',
                                            'url' => ['/products/mobile', $item->id],
                                            'style' => 'display:inline',
                                            'id' => 'delete_item_form'
                                        ]) !!}
                                            {!! Form::button('<span class="glyphicon glyphicon-trash waves-effect" aria-hidden="true" title="Delete Mobile" />', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'title' => 'Delete Mobile',
                                                    'data-type' => 'confirm',
                                                    'id' => 'confirm_delete'
                                            )) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pagination-wrapper"> {!! $mobile->render() !!} </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="/theme/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Jquery DataTable Plugin Js -->
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="/theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="/theme/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="/theme/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="/theme/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="/theme/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="/theme/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="/theme/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="/theme/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
@endsection
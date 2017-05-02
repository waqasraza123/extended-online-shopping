@extends('layouts.app')
@section('head')
    <link href="/theme/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Mobile {{ $mobile->id}}</div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Brand</th>
                                    <th>Colors</th>
                                    <th>Storage</th>
                                    <th>Stock</th>
                                    <th>Price(RS)</th>
                                    <th>Discount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>{{ $mobile->id }}</td>
                                    <td>{{ $mobile->mobile->title }}</td>
                                    <td>{{ \App\Brand::find($mobile->mobile->brand_id)->name }}</td>
                                    <td id="colors_text_box">
                                        @foreach($mobile->colors as $color)
                                            <span>
                                                {{ trim($color->color)}}
                                                @if(!$loop->last)
                                                    {{'|'}}
                                                @endif
                                            </span>
                                        @endforeach
                                    </td>

                                    <td>
                                        @foreach($mobile->storages as $s)
                                            {{$s->storage}}
                                            @if(!$loop->last)
                                                {{'|'}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{$mobile->stock}}</td>
                                    <td>{{number_format($mobile->current_price)}}</td>
                                    <td>{{$mobile->discount}}</td>

                                    <td>
                                        <a href="{{ url('products/mobile/' . $mobile->mobile->id. '/edit') }}" class="btn btn-primary btn-xs" title="Edit Mobile"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
                                        {!! Form::open([
                                            'method'=>'DELETE',
                                            'url' => ['/products/mobile', $mobile->id],
                                            'style' => 'display:inline',
                                            'class' => 'delete_item_form'
                                        ]) !!}
                                        {!! Form::button('<span class="glyphicon glyphicon-trash waves-effect" aria-hidden="true" title="Delete Mobile" />', array(
                                                'type' => 'submit',
                                                'class' => 'btn btn-danger btn-xs confirm_delete',
                                                'title' => 'Delete Mobile',
                                                'data-type' => 'confirm'
                                        )) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="/theme/plugins/sweetalert/sweetalert.min.js"></script>
@endsection
@extends('layouts.app')
@section('page-header', 'Sales')
@section('subheading', 'View Your Sales Summary')
@section('content')
    <ul class="timeline">
        @if(count($sales) > 0)
            @foreach($sales as $sale)
                <!-- timeline time label -->
                <li class="time-label">
                <span class="bg-red">
                    {{$sale->updated_at}}
                </span>
                </li>
                <!-- /.timeline-label -->

                <!-- timeline item -->
                <li>
                    <!-- timeline icon -->
                    <i class="fa fa-mobile bg-blue"></i>
                    <div class="timeline-item">
                        <h3 class="timeline-header"><a href="#">{{$sale->product->mobile->title}}</a></h3>

                        <div class="timeline-body">

                        </div>

                        <div class="timeline-footer">
                            <a class="btn btn-primary btn-xs">Stock: {{$sale->product->stock}}</a>
                            <a class="btn btn-primary btn-xs">Sold At: {{$sale->revenue}}</a>
                        </div>
                    </div>
                </li>
            @endforeach
        @else
            <div class="callout callout-danger">
                No Sales Yet.
            </div>
        @endif
        <!-- END timeline item -->
    </ul>
@endsection
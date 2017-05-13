@extends('layouts.frontend')
@include('partials.map-modal')
@section('map')
    @include('layouts.partials.frontend.map')
@endsection
@section('content')
    <div class="row clearfix welcome">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-20">
            <div>
                <div class="body">
                    @if($data)
                        @foreach($data as $i => $v)
                            @foreach($v as $index => $value)
                                @include('frontend.partials.welcome-content-areas', ['mobiles' => $value, 'category' => $index])
                            @endforeach
                        @endforeach
                    @else
                        <div class="callout callout-danger">
                            <h4>
                                No Data
                            </h4>
                            <p>
                                No Products Added Yet.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="/theme/js/pages/ui/tooltips-popovers.js"></script>
@endsection
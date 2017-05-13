{!! Form::open(['url' => route('search'), 'class' => 'form-horizontal', 'id' => 'search_form', 'method' => 'get']) !!}
<div class="col-md-12 col-lg-12 search-box">
    <div class="col-sm-4">
        <input id="search_box" placeholder="Search" type="text" name="search_text" class="animated form-control" value="{{isset($searchText) == false ? "" : $searchText}}">
    </div>

    <div class="col-sm-4">
        <input placeholder="Enter Market Location" type="text" name="market_location" class="form-control" id="market_location" value="{{isset($marketLocation) ? $marketLocation : ""}}">
    </div>

    <div class="col-sm-4">
        <input placeholder="Enter Your Location" type="text" name="user_location" class="form-control" id="user_location" value="{{isset($userLocation) ? $userLocation : ''}}">
    </div>

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-2" style="margin-top: 44px">
                        <label class="text-black float-left" for="radius">Radius(Km)</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="range-slider">
                            <div class="row">
                                <div class="col-sm-11">
                                    <input class="range-slider__range" type="range" value="0" min="0" max="50" name="radius">
                                </div>
                                <div class="col-sm-1">
                                    <span class="range-slider__value">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4" style="margin-top: 30px">
                <input id="search-btn" type="submit" value="Search" class="btn bg-maroon btn-flat margin form-control">
            </div>
        </div>
    </div>


    <input type="hidden" id="lat" value="{{$marketLat or ""}}" name="lat">
    <input type="hidden" id="long" value="{{$marketLong or ""}}" name="long">
    <input type="hidden" id="user_lat" value="{{$userLat or ""}}" name="user_lat">
    <input type="hidden" id="user_long" value="{{$userLong or ""}}" name="user_long">
</div>
{!! Form::close() !!}
@section('footer')
    <script>
        var rangeSlider = function(){
            var slider = $('.search-box .range-slider'),
                    range = $('.search-box .range-slider__range'),
                    value = $('.search-box .range-slider__value');

            range.on('input', function(){
                range.val(this.value)
                $(this).parent().next().find(".range-slider__value").html(this.value);
            });
        };

        rangeSlider();
    </script>
@endsection
{!! Form::open(['url' => route('search'), 'class' => 'form-horizontal', 'id' => 'search_form']) !!}

    <div class="col-sm-12 form-group-sm margin-top-20">
        <input placeholder="Search" type="text" name="search_text" class="form-control" value="{{isset($searchText) == false ? "" : $searchText}}">
    </div>

    <div class="col-sm-12 form-group-sm margin-top-20">
        <label class="text-white">Market Location</label>
        <input placeholder="Enter Market Location" type="text" name="market_location" class="form-control" id="market_location" value="">
    </div>

    {{--<div class="col-sm-12 form-group-sm margin-top-20">
        <label class="" for="radius">Nearby(Km)</label>
        <input type="text" id="radius_slider" name="radius" class="form-control" value="">
    </div>--}}

    {{--<div class="col-sm-12 form-group-sm">
        <label class="" for="price-range">Price</label>
        <input type="text" id="price_slider" name="price-range" class="form-control" value="">
    </div>--}}
    <input type="hidden" id="lat" value="" name="lat">
    <input type="hidden" id="long" value="" name="long">
    <div class="col-sm-12 form-group-sm margin-top-20">
        <input type="submit" value="Search" class="btn btn-lg btn-primary form-control">
    </div>
{!! Form::close() !!}
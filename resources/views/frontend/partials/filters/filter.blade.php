{!! Form::open(['url' => route('search'), 'class' => 'form-horizontal', 'id' => 'search_form', 'method' => 'get']) !!}

    <div class="col-sm-12 form-group-sm margin-top-20">
        <input id="search_box" placeholder="Search" type="text" name="search_text" class="animated form-control" value="{{isset($searchText) == false ? "" : $searchText}}">
    </div>

    <div class="col-sm-12 form-group-sm margin-top-20">
        <label class="text-white">Specify Market Location</label>
        <input placeholder="Enter Market Location" type="text" name="market_location" class="form-control" id="market_location" value="{{isset($marketLocation) ? $marketLocation : ""}}">
    </div>

    <div class="col-sm-12 form-group-sm margin-top-20">
        <label class="text-white">Your Location</label>
        <input placeholder="Enter Your Location" type="text" name="user_location" class="form-control" id="user_location" value="{{isset($userLocation) ? $userLocation : ''}}">
    </div>

    <div class="col-sm-12 form-group-sm margin-top-20">
        <label class="text-white" for="radius">Select Radius(Km)</label>
        <p class="text-white">Ignores Market Location</p>
        <div class="range-slider">
            <input class="range-slider__range" type="range" value="0" min="0" max="50" name="radius">
            <span class="range-slider__value">0</span>
        </div>
    </div>


    <input type="hidden" id="lat" value="{{$marketLat or ""}}" name="lat">
    <input type="hidden" id="long" value="{{$marketLong or ""}}" name="long">
    <input type="hidden" id="user_lat" value="{{$userLat or ""}}" name="user_lat">
    <input type="hidden" id="user_long" value="{{$userLong or ""}}" name="user_long">
    <div class="col-sm-12 form-group-sm margin-top-20">
        <input id="search-btn" type="submit" value="Search" class="btn btn-lg btn-primary form-control">
    </div>
{!! Form::close() !!}
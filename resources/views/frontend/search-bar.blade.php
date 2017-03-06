<div class="row">
    <div class="col-sm-12">
        {!! Form::open(['url' => route('search'), 'class' => 'form-horizontal', 'id' => 'search_form']) !!}
        <div class="row">
            <div class="col-sm-10">
                <input type="text" name="search_text" class="form-control" value="{{isset($searchText) == false ? "" : $searchText}}">
            </div>
            <div class="col-sm-2">
                <input type="submit" name="search_button" class="btn bg-teal btn-block btn-lg waves-effect" value="Search">
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<div class="row">
    <div class="card">
        @section('page-header')
            Edit {{$mobile->title}}
        @endsection
        <div class="body">
            {{--<div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="title">Product Image</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="file" id="product_image" name="product_image" class="form-control"
                                   value="{{$mobile->image}}" required>
                        </div>
                    </div>
                </div>
            </div>--}}
            <div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="title">Title</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="hidden" name="mobile_id" value="{{$mobile->id}}">
                            {!! Form::text('title', null, ['id' => 'title', 'class' => 'form-control',
                            'placeholder' => 'Name of mobile', 'required']) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{--prices of the mobile, current and old--}}
            <div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="current_price">Price(Rs)</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::text('old_price', $mobile->data()->where('shop_id', $shopId)->first()->old_price, ['class' => 'form-control price', 'id' => 'current_price',
                            'placeholder' => '10000', 'required']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="discount_price">Discount Price(Rs)</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::text('current_price', $mobile->data()->where('shop_id', $shopId)->first()->current_price, ['class' => 'form-control price', 'id' => 'discount_price',
                            'placeholder' => '10000']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="stock">Stock Quantity</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::text('stock', $mobile->data()->where('shop_id', $shopId)->first()->stock, ['class' => 'form-control price', 'id' => 'stock',
                            'placeholder' => 'please enter quantity', 'required']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="brand">Select Brand</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::select('brands', $brands, $mobile->brand->id, ['class' => 'form-control',
                            'id' => 'brands', 'placeholder' => 'Select or Type', 'required']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="colors">Select Color(s)</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::select('colors[]', $mobile->colors()->pluck('colors.color', 'colors.id')->toArray(),
                            $mobile->data()->where('shop_id', $shopId)->first()->colors()->pluck('colors.id')->toArray(), ['multiple' => 'true',
                            'class' => 'form-control',
                            'id' => 'colors',
                            'data-placeholder' => 'Select or Type']) !!}
                        </div>
                    </div>
                </div>
            </div>
            {{--storage capacity--}}
            <div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="colors">Internal Storage(GB)</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::select('storage[]', $mobile->storages()->pluck('storages.storage', 'storages.id')->toArray(),
                            $mobile->data()->where('shop_id', $shopId)->first()->storages()->pluck('storages.id')->toArray(), ['multiple' => 'true',
                            'class' => 'form-control',
                            'id' => 'storage',
                            'data-placeholder' => 'Select or Type']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" value="l" name="local_online">
            <div class="row clearfix">
                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect submit" id="add_mobile_btn">Update Mobile</button>
                </div>
            </div>
        </div>
    </div>
</div>
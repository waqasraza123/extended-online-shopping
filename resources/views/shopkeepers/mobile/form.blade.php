<div class="row add-one-form">
    <div class="card">
        <div class="header">
            <h2>
                Add New Mobile
            </h2>
        </div>
        <div class="body">
            <div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="brand">Select Brand</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::select('brands', $brands, null, ['class' => 'form-control brands-class',
                            'id' => 'brands', 'placeholder' => 'Select or Type', 'required']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix hide-field title-field">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="title">Title</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            <select type="text" id="title" name="title" class="form-control title" required></select>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="row clearfix hide-field">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="title">Product Image</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="file" id="product_image" name="product_image" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>--}}
            <input type="hidden" name="single_mobile_id" value="" id="single_mobile_id">
            {{--prices of the mobile, current and old--}}
            <div class="row clearfix hide-field">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="current_price">Old Price(Rs)</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::text('old_price', null, ['class' => 'form-control price', 'id' => 'current_price',
                            'placeholder' => 'Old Price', 'required']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix hide-field">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="discount_price">Discount Price(Rs)</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::text('current_price', null, ['class' => 'form-control price', 'id' => 'discount_price',
                            'placeholder' => 'Current Price']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix hide-field">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="stock">Stock Quantity</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::text('stock', null, ['class' => 'form-control price', 'id' => 'stock',
                            'placeholder' => 'please enter quantity', 'required']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix hide-field">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="colors">Select Color(s)</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::select('colors[]', $colors, null, ['multiple' => 'true',
                            'class' => 'form-control colors',
                            'id' => 'colors',
                            'data-placeholder' => 'Select or Type']) !!}
                        </div>
                    </div>
                </div>
            </div>
            {{--storage capacity--}}
            <div class="row clearfix hide-field">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="storage">Internal Storage(GB)</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::select('storage[]', $storage, null, ['multiple' => 'true',
                            'class' => 'form-control storage',
                            'id' => 'storage',
                            'data-placeholder' => 'Select or Type']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" value="l" name="local_online">
            <div class="row clearfix">
                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect submit" id="add_mobile_btn">Add Mobile</button>
                </div>
            </div>
        </div>
    </div>
</div>
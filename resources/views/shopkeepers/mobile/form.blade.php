<div class="row">
    <div class="card">
        <div class="header">
            <h2>
                Add New Mobile
            </h2>
            <ul class="header-dropdown m-r--5">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="javascript:void(0);">Action</a></li>
                        <li><a href="javascript:void(0);">Another action</a></li>
                        <li><a href="javascript:void(0);">Something else here</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="row clearfix">
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
            </div>
            <div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="title">Title</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="title" name="title" class="form-control" placeholder="name of mobile" required>
                        </div>
                    </div>
                </div>
            </div>

            {{--prices of the mobile, current and old--}}
            <div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="current_price">Current Price(Rs)</label>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::text('current_price', null, ['class' => 'form-control price', 'id' => 'current_price',
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
                            {!! Form::text('discount_price', 0, ['class' => 'form-control price', 'id' => 'discount_price',
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
                            {!! Form::text('stock', null, ['class' => 'form-control price', 'id' => 'stock',
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
                            {!! Form::select('brands', $brands, null, ['class' => 'form-control',
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
                            {!! Form::select('colors[]', $colors, null, ['multiple' => 'true',
                            'class' => 'form-control',
                            'id' => 'colors',
                            'data-placeholder' => 'Select or Type', 'required']) !!}
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
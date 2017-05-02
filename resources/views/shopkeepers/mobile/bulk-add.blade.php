<form id="bulk-data-form">
    <div class="row bulk-add-form">
        <div class="">
            <div class="header">
                <h2 class="text-center">
                    Bulk Add Mobiles
                </h2>
            </div>
            <div class="body" id="bulk-data-area">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-3"></div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                        <label for="brand">Select Brand</label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="">
                                {!! Form::select('brands', $brands, null, ['class' => 'form-control',
                                'id' => 'bulk-brands', 'placeholder' => 'Select or Type', 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3"></div>
                </div>


                <input type="hidden" value="l" name="local_online">
                <div class="row clearfix">
                    <div style="text-align: center">
                        <button type="submit" class="btn btn-primary m-t-15 waves-effect submit" id="add_bulk_mobile_btn">Add Mobiles</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
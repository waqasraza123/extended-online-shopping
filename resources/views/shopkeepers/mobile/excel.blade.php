<form id="excel-data-form" action="{{route('save-excel')}}" method="POST" enctype="multipart/form-data">
    <input type="hidden" value="{{csrf_token()}}" name="_token">
    <div class="row excel-add-form">
        <div class="card">
            <div class="header">
                <h2>
                    Import Excel/CSV
                </h2>

            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="excel-data">Select File</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::file('excel-data', ['class' => 'form-control',
                                'id' => 'excel-data', 'required',
                                'accept' => '.xls,.xlsx',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                        <button type="submit" class="btn btn-primary m-t-15 waves-effect submit" id="add_mobile_btn">Import</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
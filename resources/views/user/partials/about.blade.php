<div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title">About Me</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

        <p class="text-muted">{{Auth::user()->location == "" ? "Not Specified" : Auth::user()->location}}</p>

        <hr>

        <strong><i class="fa fa-pencil margin-r-5"></i> Badges</strong>

        <p>
            <span class="label label-success">Verified</span>
            <span class="label label-info">Best Seller</span>
            <span class="label label-primary">Top Seller</span>
        </p>
    </div>
    <!-- /.box-body -->
</div>
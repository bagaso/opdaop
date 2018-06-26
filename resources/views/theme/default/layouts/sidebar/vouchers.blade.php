<div class="col-md-3">
    <div class="box box-solid">
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                @if(auth()->user()->group->id !== 5)
                <li class="{{ request()->getUri() === route('vouchers') ? 'active' : '' }}"><a href="{{ route('vouchers') }}">Generate Voucher<span class="label label-primary pull-right">12</span></a></li>
                @endif
                <li class="{{ request()->getUri() === route('vouchers.apply') ? 'active' : '' }}"><a href="{{ route('vouchers.apply') }}">Apply Voucher<span class="label label-primary pull-right">12</span></a></li>
            </ul>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /. box -->
</div>
<!-- /.col -->
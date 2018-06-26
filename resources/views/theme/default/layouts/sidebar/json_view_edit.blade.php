<div class="col-md-3">
    <div class="box box-primary">
        <div class="box-body box-profile">
            <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                    <b><small>Slug Url</small></b> <a class="pull-right"><small>/json/{{ $json->slug_url}}</small></a>
                </li>
                <li class="list-group-item">
                    <b><small>Version</small></b> <a class="pull-right"><small>{{ $json->version }}</small></a>
                </li>
                <li class="list-group-item">
                    <b><small>Enable</small></b> <a class="pull-right"><span class="label label-{{ $json->is_enable ? 'success' : 'danger' }}">{{ $json->is_enable? 'Yes' : 'No' }}</span></a>
                </li>
                <li class="list-group-item">
                    <b><small>Last Update</small></b> <a class="pull-right"><small>{{ $json->updated_at }}</small></a>
                </li>
                <li class="list-group-item">
                    <b><small>Date Created</small></b> <a class="pull-right"><small>{{ $json->created_at }}</small></a>
                </li>
            </ul>
        </div>
        <!-- /.box-body -->
    </div>
</div>
<!-- /.col -->
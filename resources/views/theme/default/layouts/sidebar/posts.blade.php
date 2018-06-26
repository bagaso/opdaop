<div class="col-md-3">
    <div class="box box-primary">
        <div class="box-body box-profile">
            <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                    <b>
                        Post By
                    </b>
                    <a class="pull-right">
                    @auth
                        @if(auth()->user()->isAdmin() || $post->user_id == auth()->user()->id)
                            {{ $post->user->username }}
                        @else
                            <span class="label label-{{ $post->user->group->class }}">{{ $post->user->group->name }}</span>
                        @endif
                    @endauth
                    @guest
                        <span class="label label-{{ $post->user->group->class }}">{{ $post->user->group->name }}</span>
                    @endguest
                    </a>
                </li>
                <li class="list-group-item">
                    <b>Status</b> <a class="pull-right"><span class="label label-{{ $post->is_public ? 'success' : 'danger' }}">{{ $post->is_public ? 'Public' : 'Private' }}</span></a>
                </li>
                <li class="list-group-item">
                    <b>Last Update</b> <a class="pull-right">{{ $post->updated_at }}</a>
                </li>
                <li class="list-group-item">
                    <b>Date Created</b> <a class="pull-right">{{ $post->created_at }}</a>
                </li>
            </ul>
            @auth
                @if(auth()->user()->can('MANAGE_POST', $post->id))
                    <a href="{{ route('news_and_updates.edit', $post->id) }}" class="btn btn-primary btn-block"><b>Edit Post</b></a>
                @endif
            @endauth
        </div>
        <!-- /.box-body -->
    </div>
</div>
<!-- /.col -->
<form action="{{ route('statuses.store') }}" method="post">

    @include('shared.errors')

    {{ csrf_field() }}

    <textarea class="form-control" rows="3" placeholder="聊聊新鲜事儿..." name="content">
    </textarea>
    <button type="submit" class="btn-primary btn pull-left"> 发布 </button>

</form>
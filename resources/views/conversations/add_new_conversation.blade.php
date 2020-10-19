
@extends ("layout")
@section("content")
<h4 style="      margin-left: 433px;
margin-top: 47px;

">Add New Conversation</h4>
<form action="{{url('/user/add-new-conversation/sphere/'.$sphereId.'/user/'.$userId)}}" method="post"style="    margin-top: 69px;
margin-left: 393px;
width: 50%;"enctype="multipart/form-data">{{csrf_field()}}
<div class="form-group">
    <label for="title"class="form-label">title</label>
    <input name="title" class="form-control" id="title" placeholder="title">
</div>
<div class="form-group">
    <label for="description"class="form-label">description</label>
    <input name="description" class="form-control" id="description" placeholder="description">
</div>

<button name="submit" class="btn btn-primary"style="margin-left: 323px;">create</button>
</form>
@endsection


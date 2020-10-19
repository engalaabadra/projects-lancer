
@extends ("layout")
@section("content")
<h4 style="      margin-left: 433px;
margin-top: 47px;

">Add New Project Into Conversation : 
@if($conversationCount)
<a href="{{url('/view-conversation/'.$conversationId.'/sphere/'.$sphereId)}}">{{$conversation->title}}</a> </h4>
@endif
<form action="{{url('/user/add-project/conversation/'.$conversationId.'/sphere/'.$sphereId.'/user/'.$userId.'/topic/'.$theLastTopic->id)}}" method="post"style="margin-top: 69px;margin-left: 122px;"enctype="multipart/form-data">{{csrf_field()}}
<div class="form-group">
    <label for="name"class="form-label">name</label>
    <input name="name" class="form-control" id="name" placeholder="name">
</div>
<div class="form-group">
    <label for="image_project"class="form-label">image_project</label>
    <input name="image_project" class="form-control" id="image_project" type="file">
</div>
<div class="form-group">
    <label for="description"class="form-label">description</label>
    <input name="description" class="form-control" id="description" placeholder="description">
</div>
<button name="submit" class="btn btn-primary"style=" margin-left: 492px;">create</button>
</form>
@endsection

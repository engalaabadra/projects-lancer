
@extends ("layout")
@section("content")
<h4 style="      margin-left: 433px;
margin-top: 47px;

">Add New Topic</h4>
<form action="{{url('/user/add-new-topic/specific-conversation/sphere/'.$sphereId.'/user/'.$userId)}}" method="post"style="    margin-top: 69px;
margin-left: 393px;
width: 50%;"enctype="multipart/form-data">{{csrf_field()}}
<div class="form-group">
    <label for="name"class="form-label">name</label>
    <input name="name" class="form-control" id="name" placeholder="name">
</div>

<div class="form-group">
    <label for="conversation_id"class="form-label">conversation_name</label>
    <select name="conversation_id" id="" class="form-control">
      @if($conversationsSphereCount!==0)
        @foreach($conversationsSphere as $conversationSphere)
        <option value="{{$conversationSphere->id}}">{{$conversationSphere->title}}</option>
        @endforeach
      @endif
    </select>
  </div>
<button name="submit" class="btn btn-primary"style="margin-left: 323px;">create</button>
</form>
@endsection


@extends ("layout")
@section("content")
<form action="{{url('/user/edit-project/'.$project->id.'/'.$sphere_id)}}" method="post"style="margin-top: 69px;margin-left: 122px;"enctype="multipart/form-data">{{csrf_field()}}
    <div class="form-group">
        <label for="name"class="form-label">name</label>
        <input name="name" class="form-control" id="name" placeholder="name" value="{{$project->name}}">
    </div>
    <div class="form-group">
        <label for="description"class="form-label">description</label>
        <input name="description" class="form-control" id="description" placeholder="description" value="{{$project->description}}">
    </div>
        <button name="submit" class="btn btn-primary"style=" margin-left: 492px;">edit project</button>
</form>
@endsection

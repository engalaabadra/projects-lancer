
@extends ("layout")
@section("content")
<form action="{{url('/user/add-project/'.$sphere->id)}}" method="post"style="margin-top: 69px;margin-left: 122px;"enctype="multipart/form-data">{{csrf_field()}}
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

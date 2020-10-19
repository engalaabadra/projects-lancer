
@extends ("layout")
@section("content")
<h1 style="     margin-left: 520px;
">Create Room</h1>
<form action="{{url('/create-room/'.$sphereId)}}" method="post"style="    margin-top: 62px;
margin-left: 363px;
width: 50%;"enctype="multipart/form-data">{{csrf_field()}}
    <div class="form-group">
        <label for="title"class="form-label">title</label>
        <input name="title" class="form-control" id="title" placeholder="title">
    </div>
    <div class="form-group">
        <label for="description"class="form-label">description</label>
        <input name="description" class="form-control" id="description" placeholder="description" type="text">
    </div>
    <button name="submit" class="btn btn-primary"style="margin-left: 320px;">create</button>
</form>
@endsection

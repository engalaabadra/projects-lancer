
@extends ("layout")
@section("content")
<form action="{{url('/user/edit-sphere/'.$sphereId)}}" method="post"style="margin-top: 69px;margin-left: 122px;"enctype="multipart/form-data">{{csrf_field()}}
  <div class="form-group">
    <label for="sphere"class="form-label">sphere</label>
    <select name="name" id="" class="form-control">
    @foreach($MainSpheres as $sphere)
      <option value="{{$sphere->name}}">{{$sphere->name}}</option>
    @endforeach
    </select>
  </div>
  <div class="form-group">
    <label for="description"class="form-label">description</label>
    <input name="description" class="form-control" id="description" placeholder="description"value="{{$sphere->description}}">
  </div>

  <button name="submit" class="btn btn-primary"style=" margin-left: 492px;">edit sphere</button>
</form>
@endsection


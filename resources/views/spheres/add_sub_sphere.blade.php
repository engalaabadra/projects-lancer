
@extends ("layout")
@section("content")
<form action="{{url('/user/add-sphere/')}}" method="post" style="margin-top: 69px;margin-left: 122px;"enctype="multipart/form-data">{{csrf_field()}}
  <div class="form-group">
    <label for="sphere"class="form-label">sphere</label>
    <select name="parent_sphere" id="" class="form-control">
    @if($MainSpheresCount!==0)
      @foreach($MainSpheres as $sphere)
      <option value="{{$sphere->name}}">{{$sphere->name}}</option>
      @endforeach
    @endif
    </select>
  </div>
  <div class="form-group">
    <label for="name_sphere"class="form-label">name_sphere</label>
    <input name="name_sphere" class="form-control" id="name_sphere" placeholder="name_sphere">
  </div>
  <div class="form-group">
    <label for="image_sphere"class="form-label">image_sphere</label>
    <input name="image_sphere" class="form-control" id="image_sphere"  type="file">
  </div>
  <div class="form-group">
    <label for="description_sphere"class="form-label">description_sphere</label>
    <input name="description_sphere" class="form-control" id="description_sphere" placeholder="description">
  </div>
  <div class="form-group">
    <label for="primary_focus"class="form-label">primary_focus</label>
    <input name="primary_focus" class="form-control" id="primary_focus" placeholder="primary_focus">
  </div>

  
  <button name="submit" class="btn btn-primary"style=" margin-left: 492px;">create</button>
</form>
@endsection

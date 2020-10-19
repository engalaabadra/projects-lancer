
@extends ("layout")
@section("content")
<form action="{{url('/add-project')}}" method="post"style="margin-top: 69px;margin-left: 122px;"enctype="multipart/form-data">{{csrf_field()}}
  <label for="sphere_id" class="form-label">All spheres</label>
    <select name="sphere_id" id=""style="margin-left: 488px;" class="form-control">
    <?php echo $dropdown?>
  </select>
  <div class="form-group">
    <label for="name"class="form-label">name</label>
    <input name="name" class="form-control" id="name" placeholder="name">
  </div>
  <div class="form-group">
    <label for="description"class="form-label">description</label>
    <input name="description" class="form-control" id="description" placeholder="description">
  </div>
  <button name="submit" class="btn btn-primary"style=" margin-left: 492px;">create</button>
</form>
@endsection

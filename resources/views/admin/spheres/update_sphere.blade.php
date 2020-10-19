
@extends ("layout")
@section("content")
<form action="{{url('/user/update-sphere/'.$sphere)}}" method="post"style="margin-top: 69px;margin-left: 122px;"enctype="multipart/form-data">{{csrf_field()}}
    <div class="form-group">
        <label for="sphere"class="form-label">sphere</label>
        <select name="parent_sphere" id="" class="form-control">
        @foreach($MainSpheres as $sphere)
            <option value="{{$sphere->name}}">{{$sphere->name}}</option>
        @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="name_"class="form-label">name</label>
        <input name="name_" class="form-control" id="name_" placeholder="description" value="{{$sphere->name}}">
    </div>
    <div class="form-group">
        <label for="image_sphere"class="form-label">image_sphere</label>
        <input name="image_sphere" class="form-control" id="image_sphere" placeholder="description" type="text" >
    </div>
    <div class="form-group">
        <label for="description"class="form-label">description</label>
        <input name="description" class="form-control" id="description" placeholder="description" value="{{$sphere->description}}">
    </div>

    <button name="submit" class="btn btn-primary"style=" margin-left: 492px;">update</button>
</form>
@endsection

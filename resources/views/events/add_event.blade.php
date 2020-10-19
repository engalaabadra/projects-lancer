
@extends ("layout")
@section("content")
<form action="{{url('/schedule-event/'.$sphereId)}}" method="post"style="margin-top: 69px;margin-left: 122px;"enctype="multipart/form-data">{{csrf_field()}}
    <div class="form-group">
        <label for="title_event"class="form-label">title_event</label>
        <input name="title_event" class="form-control" id="title_event" placeholder="title_event">
    </div>
    <div class="form-group">
        <label for="description_event"class="form-label">description_event</label>
        <input name="description_event" class="form-control" id="description_event" placeholder="description_event" type="text">
    </div>
    <div class="form-group">
        <label for="event_time"class="form-label">event_time</label>
        <input name="event_time" class="form-control" id="event_time" placeholder="event_time" type="date">
    </div>
        <input name="created_at" class="form-control" id="created_at" placeholder="created_at" type="hidden">
    <button name="submit" class="btn btn-primary"style=" margin-left: 492px;">create</button>
    <div class="alert alert-danger" style="    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
    width: 24%;
    margin-left: 493px;
    margin-top: 0px;">
        <ul>
          @if($errors->any())
          @foreach($errors->all() as $err)
            <li>{{$err}}</li>
          @endforeach
          @endif
          </ul>
        </div>
</form>
@endsection

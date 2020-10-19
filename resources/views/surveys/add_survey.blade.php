

@extends ("layout")
@section("content")
<form method="POST" action="{{url('/add-survey/'.$sphere_id)}}" >
    {{csrf_field()}}
    <label for="title"class="form-label">title</label>
    <input type="text"  name="title"  class="form-control">
    <label for="description"class="form-label">description</label>
    <input type="text"  name="description" class="form-control" >
    <label class="form-label">options</label>
    <input type="text"  name="first_option"  class="form-control">
    <input type="text"  name="second_option"  class="form-control">
    <button type="submit">Send</button>
</form>
@endsection

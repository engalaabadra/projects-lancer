@extends ("layout")
@section("content")

<div class="alert alert-success" >
    you cannot join again in this sphere , pls only wait accepting on your joining , you can click  <a href="{{url('/sphere/'.$sphere_id.'/posts')}}">here</a> to return into sphere
</div>
@endsection

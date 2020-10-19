

@extends ("layout")
@section("content")
<div class="alert alert-warning" role="alert">
    <h4 >You can not see this page , because you not member in this sphere , click  <a href="{{url('/sphere/'.$sphereId.'/posts')}}">here</a> to go  into sphere to know more  </h4>
</div>
@endsection

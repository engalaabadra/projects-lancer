@extends ("layout")
@section("content")
<div class="alert alert-warning" role="alert">
    <h4 >You can not see this page , because you not member in this sphere , click  <a href="{{url('/request-join-into-sphere/'.$userId.'/'.$sphereId)}}">here</a> to request joining into sphere  </h4>
</div>
@endsection

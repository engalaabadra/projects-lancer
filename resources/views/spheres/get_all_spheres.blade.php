@extends ("layout")
@section("content")
@if($spheresCount!==0)
    @foreach($spheres as $sphere)
        <img style="    width: 301px;height: 252px;object-fit: cover;" src="{{asset('/images/backend_images/spheres/small/'.$sphere->image)}}">
        <a href="{{url('/sphere/'.$sphere->id.'/'.'posts')}}">{{$sphere->name}}</a>
        <span>{{$sphere->description}}</span>
    @endforeach
@endif
@endsection

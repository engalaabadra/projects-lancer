@extends('layout')
@section('title')
@endsection
@section('content')
<style>
  .form-label{
    width: 24%;
    margin-left: 490px;
  }
  .form-control{
    width: 24%;
    margin-left: 490px;
    
  }
</style>
<h1 style="    margin-left: 589px;">Add Category Page</h1>
<form action="{{url('/create-post')}}" method="post"style="margin-top: 69px;margin-left: 122px;"enctype="multipart/form-data">{{csrf_field()}}
  <div class="form-group">
    <label for="title"class="form-label">title</label>
    <input name="title" class="form-control" id="title" placeholder="title">
  </div>
  <div class="form-group">
    <label for="description"class="form-label">description</label>
    <input name="description" class="form-control" id="description" placeholder="description">
  </div>
  <button name="submit" class="btn btn-primary"style=" margin-left: 492px;">create</button>
</form>
@endsection

@extends('layout')
@section('content')

<div class="header">

<h1 style="      margin-left: 652px;
    margin-top: 24px;">Edit Your Profile</h1>
</div>
@if($dataThisUserCount!==0)
  <form action="{{url('/user/edit-profile/'.$dataThisUser->id)}}" method="post"enctype="multipart/form-data"style="margin-top: 69px;
      margin-left: 122px;">{{csrf_field()}}
  <div class="form-group">
    <input type="hidden" class="form-control" id="id" placeholder="id" name="id" value="{{$dataThisUser->id}}">
    <div class="form-group">
    <label for="username" class="form-label"><b>username</b></label>
      <input name="username" class="form-control" id="username" placeholder="username" value="{{$dataThisUser->username}}">
    </div>
    <div class="form-group">
    <label for="job_title"class="form-label"><b>job_title</b></label>
      <input name="job_title" class="form-control" id="job_title" placeholder="job_title"value="{{$dataThisUser->job_title}}">
    </div>
    <div class="form-group">
      <label for="bio"class="form-label"><b>bio</b></label>
        <input name="bio" class="form-control" id="bio" placeholder="bio"value="{{$dataThisUser->bio}}">
      </div>
      <div class="form-group">
        <label for="interests"class="form-label"><b>interests</b></label>
          <input name="interests" class="form-control" id="interests" placeholder="interests"value="{{$dataThisUser->interests}}">
        </div>
    <div class="form-group">
    <label for="address"class="form-label"><b>address</b></label>
      <input name="address" class="form-control" id="address" placeholder="address"value="{{$dataThisUser->address}}">
    </div>
    <div class="form-group">
    <label for="password"class="form-label"><b>password</b></label>
      <input class="form-control" id="password" placeholder="password"value="{{$userPassDec}}" disabled>
    </div>
    <button name="submit" class="btn btn-primary" style="    margin-left: 516px;">update my profile</button>
    <a target="_blank" href="{{url('/user/update-password/'.$dataThisUser->id)}}"class="btn btn-primary" >update  my password</a><br>
    <div class="alert alert-info" style="    color: #a94442;background-color: #f2dede;border-color: #ebccd1;width: 24%;margin-left: 493px;margin-top: 0px;">
      return into   <a href="{{url('/user/view-profile/'.$dataThisUser->email)}}">my profile</a>
    </div>
  </form>
@endif
<style>
  body{
  margin:0;
  padding:0;
  background-color:#ffefef;
  }
  .form-label{
    width: 24%;
    margin-left: 490px;
  }
  .form-control{
    width: 24%;
    margin-left: 490px;
  }
  .header{
  margin-top: 43px;
    font-family: fantasy;
}
.header:hover{
  color:#ff00bc;
}
</style>
@endsection

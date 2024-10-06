
@extends('layout')

@section('title')
    Category Page
@endsection

@section('content')
@if(Session::has('flash_message_error'))
    <div class="alert alert-error alert-block">
        <strong>{!!session('flash_message_error')!!}</strong>
    </div>
@endif

@if(Session::has('flash_message_success'))
    <div class="alert alert-success alert-block">
        <strong>{!!session('flash_message_success')!!}</strong>
    </div>
@endif


<form  method="POST"  action="{{url('/user/login')}}">
{{csrf_field()}}
  <div class="imgcontainer">
  </div>

  <div class="container">

  <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email" required>

    <label for="password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>

    <button type="submit" >Login</button>
  </div>


</form>
Forgot <a href="{{url('/user/forgot-password')}}">password?</a>
<a href="/user/reg">register</a>
By Using :
<a href="auth/facebook/">facebook</a>
<a href="auth/github/">github</a>
<a href="auth/google/">google</a>
@endsection
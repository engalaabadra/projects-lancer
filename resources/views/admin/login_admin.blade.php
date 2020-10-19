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
<form method="POST"  action="{{url('/admin/login')}}">
{{csrf_field()}}
  <div class="container">
    <h1>login admin</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>
    <input type="hidden" name="id">
    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email" required>
    <label for="password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>
    <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>
    <button type="submit" class="registerbtn">Register</button>
  </div>
  <div class="container signin">
    <p>Already have an account? <a href="#">Sign in</a>.</p>
  </div>
  <div class="container" style="background-color:#f1f1f1">
    <button type="button" class="cancelbtn">Cancel</button>
    <span class="psw">Forgot <a href="{{url('/forgot-password')}}">password?</a></span>
  </div>
</form>

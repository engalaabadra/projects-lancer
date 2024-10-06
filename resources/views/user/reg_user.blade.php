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
<form method="POST" name="registerForm" action="{{url('/user/reg')}}">
{{csrf_field()}}

  <div class="container">
    <h1>Register</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>
    <input type="text" placeholder="username" name="username" required>
  <input type="hidden" name="id">
    <input type="text" placeholder="Enter Email" name="email" required>

    <input type="password" placeholder="Enter Password" name="password" required>

    <input type="password" placeholder="Repeat Password" name="password-repeat" required>
    <button type="submit" class="registerbtn">Register</button>
  </div>
  <div class="container signin">
    <p>Already have an account? <a href="/user/login">Sign in</a>.</p>
  </div>
</form>
<a href="auth/facebook">facebook</a>
<a href="auth/github">github</a>
<a href="auth/google">google</a>
@endsection
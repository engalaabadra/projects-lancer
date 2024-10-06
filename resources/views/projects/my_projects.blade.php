@extends('layout')

@section('content')
    <div id="app">
        <myprojects user_id={{$user_id}}  />
    </div>

    @endsection


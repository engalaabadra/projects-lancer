@extends('layout')

@section('content')
    <div id="app">
        <mytasks user_id={{$user_id}}  />
    </div>

    @endsection


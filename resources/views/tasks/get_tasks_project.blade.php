@extends('layout')

@section('content')
    <div id="app">
        @if($leadersSphereAcceptedRequestCount!=0||$leadersSphereAcceptedInvitationCount!=0||$founderSphereCount!=0)
        <Board user_id={{$user_id}} sphere_id={{$sphere_id}} project_id={{$project_id}} leaders_sphere_accepted_invitation={{$leadersSphereAcceptedInvitation}} leaders_sphere_accepted_request={{$leadersSphereAcceptedRequest}}  />
        @endif
    </div>

    @endsection


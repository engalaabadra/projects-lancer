@extends('layout')
@section('content')
<div class="widget-title"> <span><i class="icon-th"></i></span>
    <div class="header">
        <h1 style="     margin-left: 590px;
        margin-bottom: 73px;">Results  Your Search</h1>
    </div>
    Results about Users
    <div class="row">
      @if($searchUserCount==0)
        <div class="alert alert-info" role="alert"style="margin-left: 202px;
            margin-top: 62px;">
        <h4>there is no results for  your search about this user</h4> 
        </div>
      @else 
        @foreach($searchUser as $search)
        <div class="col-md-3">
        <a href="{{url('user/view-profile/'.$search->email)}}"style="    margin-left: 55px;
        margin-bottom: 28px;margin-top:32px">{{$search->username}}</a>
        <div class="card"style="    width: 75%;
        margin-left: 47px;
        margin-bottom: 63px;">

        </div>
        @endforeach
      @endif


    Results about Spheres

      @if($searchSphereCount==0)
      <div class="alert alert-info" role="alert"style="margin-left: 202px;
        margin-top: 62px;">
       <h4>there is no results for  your search about this Sphere</h4> 
      </div>
      @else
      @foreach($searchSphere as $search)
      <div>
        <h5><a href="{{url('/sphere/'.$search->id.'/posts')}}">{{$search->name}}</</a>h5>
      </div>
      @endforeach
      @endif
    Results about Projects
      @if($searchProjectCount==0)
      <div class="alert alert-info" role="alert"style="margin-left: 202px;
        margin-top: 62px;">
       <h4>there is no results for  your search about this Project</h4> 
      </div>
      @else
      @foreach($searchProject as $search)
      <div>
        <h5>
          <a href="{{url('view-details-project/'.$search->sphere_id.'/'.$search->id)}}"> {{$search->name}}</a>
         
        </h5>
      </div>
      @endforeach
      @endif
    Results about Events

      @if($searchEventCount==0)
      <div class="alert alert-info" role="alert"style="margin-left: 202px;
        margin-top: 62px;">
       <h4>there is no results for  your search about this Event</h4> 
      </div>

      @else
      @foreach($searchEvent as $search)
      <div>
        <h5>
          <a href="{{url('view-details-event/'.$search->sphere_id.'/'.$search->id)}}">{{$search->title}}</a>
          
        </h5>
      </div>
      @endforeach
      @endif
    Results about Conversations

      @if($searchConversationCount==0)
      <div class="alert alert-info" role="alert"style="margin-left: 202px;
        margin-top: 62px;">
       <h4>there is no results for  your search about this Conversation</h4> 
      </div>
      @else
      @foreach($searchConversation as $search)
      <div>

        <h6><a href="{{url('/view-conversation/'.$search->id.'/sphere/'.$search->sphere_id)}}">{{$search->title}}</a></h6>
      </div>
      @endforeach
      @endif

    Results about Categories

      @if($searchCategoryCount==0)
      <div class="alert alert-info" role="alert"style="margin-left: 202px;
        margin-top: 62px;">
       <h4>there is no results for  your search about this Category</h4> 
      </div>
      @else
      @foreach($searchCategory as $search)
      <h5>{{$search->name}}</h5>
      @endforeach
      @endif

      Results about Postss

      @if($searchPostCount==0)
      <div class="alert alert-info" role="alert"style="margin-left: 202px;
        margin-top: 62px;">
       <h4>there is no results for  your search about this Posts</h4> 
      </div>
      @else
      @foreach($searchPost as $search)
      <div>
        <h5>
          <a href="{{url('/user/details-post/'.$search->id)}}">{{$search->body}}</a>
          </h5>
      </div>
      @endforeach
      @endif
@endsection
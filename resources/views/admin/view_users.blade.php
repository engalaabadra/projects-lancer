
@extends('layout')
@section('content')
<div class="widget-title"> <span><i class="icon-th"></i></span>
    <h1 style="    margin-left: 590px;">view Uers</h1>
    <div class="widget-content nopadding">
        <a class="btn btn-small btn-info text-dark ml-5"href="{{url('/admin/add-user')}}" ><i class="glyphicon glyphicon-user" aria-hidden="true"></i> add user</a>
        <table id="table_id" class="table table-bordered data-table"style="     border: 4px solid #493641;
            margin-left: 71px;
            width: 88%;
            margin-top: 38px;
            border-radius: 15%;
        ">
        <thead>
            <tr>
                <th>username</th>
                <th>email</th>
                <th>address</th>
                <th>job_title</th>
                <th>status_message</th>
                <th>invitation_status</th>
                <th>status_reg_user</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
        
        <tr class="gradeX">
            <td>{{$user->username}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->address}}</td>
            <td>{{$user->job_title}}</td>
            <td>
                @if($user->status_message=='sent message')
                    <a href="" class="btn btn-success">sent message</a>
                @else
                <a href="" class="btn btn-warning">not send message</a>
                @endif
            </td>
            <td>
                @if($user->invitation_status=='pending_inivitation_status')
                    <a href="" class="btn btn-warning">Pending</a>
                @elseif($user->invitation_status=='accepted_inivitation_status')
                <a href="" class="btn btn-success">Accepted</a>
                @endif
            </td>
            <td>
                @if($user->status_reg_user=='pending')
                    <a href="" class="btn btn-warning">Pending</a>
                @elseif($user->status_reg_user=='activated')
                <a href="" class="btn btn-success">activated</a>
                @endif
            </td>
            <td>
            <ul style=" list-style-type: none;margin-left: -46px;    margin-top: 20px;">
                <li class="nav-item" style="margin-left: 122px;margin-top: -9px;">
                    <a class="btn btn-small btn-success text-dark ml-5"href="{{url('/admin/show-answers-user/'.$user->id)}}" >Show His Answers</a>
                </li>
                <li class="nav-item" style="margin-left: -21px;margin-top: -35px;">
                    <a class="btn btn-small btn-info text-dark ml-5"href="{{url('/user/edit-user/'.$user->id)}}"style="margin-top: 3px;" >edit </a>
                <li class="nav-item" style="margin-left: 37px;margin-top: -34px;">
                    <a class="btn btn-small btn-danger text-dark ml-5"href="{{url('/user/delete-user/'.$user->id)}}" >delete</a>
                </li>
            </ul>
        </td>
        </tr>
    </tbody>
        @endforeach
    <table>
</div>
@endsection

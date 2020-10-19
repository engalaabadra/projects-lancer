
@extends('layout')
@section('title')
    VIEW spheres Page
@endsection
@section('content')

<div class="widget-title"> <span><i class="icon-th"></i></span>
                    <h1 style="    margin-left: 590px;">view sphere</h1>
<div class="widget-content nopadding">
<a class="btn btn-small btn-info text-dark ml-5"href="{{url('/admin/add-sphere')}}" ><i class="glyphicon glyphicon-sphere" aria-hidden="true"></i> add sphere</a>
<table id="table_id" class="table table-bordered data-table"style="     border: 4px solid #493641;
    margin-left: 71px;
    width: 88%;
    margin-top: 38px;
    border-radius: 15%;
">
    <thead>
        <tr>
            <th>name</th>
            <th>description</th>
            <th>status_sphere</th>

      
            <th>actions</th>
            
         
        </tr>
    </thead>
    <tbody>
@foreach($spheres as $sphere)
<?php

    //echo $decPass;
?>
        <tr class="gradeX">
 
            <td>{{$sphere->name}}</td>
            <td>{{$sphere->description}}</td>

            <td>
            @if($sphere->status_sphere=='status_pending')
                <a href="" class="btn btn-warning">Pending</a>
            @elseif($sphere->status_sphere=='status_activated')
            <a href="" class="btn btn-success">activated</a>
            @endif

            </td>


   
         
<td>
          <ul style=" list-style-type: none;
    margin-left: -46px;    margin-top: 20px;
    ">
    <?php 
      $sphereCountActivate= DB::table('spheres')->where(['id'=>$sphere->id,'status_sphere'=>'activated_status'])->count();
      $sphereCountPending= DB::table('spheres')->where(['id'=>$sphere->id,'status_sphere'=>'pending_status'])->count();
    ?>

    @if($sphereCountActivate!==0)
    <li class="nav-item" style="margin-left: 122px;
                      margin-top: -9px;">
                                        
                                        <a class="btn btn-small btn-warning text-dark ml-5"href="{{url('/admin/deactivate-sphere/'.$sphere->id)}}" >deActivate sphere</a>
                                    </li>
                                    @else
    <li class="nav-item" style="margin-left: 122px;
    margin-top: -9px;">
                      
                      <a class="btn btn-small btn-success text-dark ml-5"href="{{url('/admin/activate-sphere/'.$sphere->id)}}" >activate sphere</a>
                      </li>
@endif
                  <li class="nav-item" style="margin-left: -21px;
    margin-top: -35px;">

                      <a class="btn btn-small btn-info text-dark ml-5"href="{{url('/sphere/edit-sphere/'.$sphere->id)}}"style="margin-top: 3px;" >edit </a>
                      <li class="nav-item" style="margin-left: 37px;
    margin-top: -34px;">
                      
                      <a class="btn btn-small btn-danger text-dark ml-5"href="{{url('/sphere/delete-sphere/'.$sphere->id)}}" >delete</a>
                      </li>

              </ul>
      
          </td>
 
        </tr>
    </tbody>
@endforeach

<table>
</div>
@endsection
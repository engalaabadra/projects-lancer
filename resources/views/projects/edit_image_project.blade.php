
@extends('layout')
@section('content')

<div class="panel panel-info"style="    border-color: #bce8f1;
    width: 30%;
    margin-top: 63px;
    height: 199px;
    margin-left: 517px;">
    <div class="panel-heading"> change your project</div>
      <div class="panel-body">
        <div class="form" style="    margin-left: 89px;">
      
            <form action="{{url('/user/edit-image-project/'.$project_id.'/'.$sphere_id)}}" method="post"enctype="multipart/form-data">
                {{csrf_field()}}
                <label for="image_project">click to change  image your projecty</label>
                <input type="file" name="image_project" class="btn btn-info"style="    cursor: pointer;margin-top: 25px;"/>
                <input type="submit" name="" id="" vlaue="update my image"class="btn btn-info"style="    margin-top: 17px;">
            </form>
      </div>
      </div>
    </div>

    <style>
        body{
        margin:0;
        padding:0;
        background-color:#ffefef;
        }
    </style>
    @endsection

@extends('layout')
@section('content')
<div class="panel panel-info"style="border-color: #bce8f1;width: 30%;margin-top: 63px;height: 199px;margin-left: 517px;">
    <div class="panel-heading"> change your cover image</div>
        <div class="panel-body">
            <div class="form" style=" margin-left: 89px;">
                @if($dataThisUserCount!==0)
                    <form action="{{url('/user/change-cover-image/'.$dataThisUser->id)}}" method="post"enctype="multipart/form-data">
                    {{csrf_field()}}
                        <label for="">click to change your image</label>
                        <input type="file" name="image" class="btn btn-info"style="cursor: pointer;margin-top: 25px;"/>
                        <input type="submit" name="" id="" vlaue="update my image"class="btn btn-info"style="margin-top: 17px;">
                    </form>
                @endif
            </div>
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

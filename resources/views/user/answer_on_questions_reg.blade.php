@extends('layout')
@section('content')

<form action="/user/answer-on-questions-reg/" method="post">{{csrf_field()}}
    <label for="">Your Email To Answer on these Questions</label>
    <input type="text" name="email" id="">
    <label for="">{{$FirstQuestion->question}}</label>
    <hr>
    <br>
    <br>
    <input type="hidden" name="id_first_question" id="" value="{{$FirstQuestion->id}}">
    <input type="text" name="answer_first_question" id="">
    <div class="form-group">
        <label for="">{{$SecondQuestion->question}}</label>
        <input type="hidden" name="id_second_question" id="" value="{{$SecondQuestion->id}}">
        <select name="answer_second_question1" id="" class="form-control">
          @if($spheresCount!==0)
            @foreach($spheres as $sphere)
              <option value="{{$sphere->name}}">{{$sphere->name}}</option>
            @endforeach
          @endif
        </select>

    </div>
    <label for="">{{$ThirdQuestion->question}}</label>
    <input type="hidden" name="id_third_question" id="" value="{{$ThirdQuestion->id}}">
    @if($optionsRegForThirdQCount!==0)
      options:
      @foreach($optionsRegForThirdQ as $optionRegForThirdQ)
        <input type="hidden" name="option_id" id="" value="{{$optionRegForThirdQ->id}}">
        <input type="radio" name="answer_third_question" id="" value="{{$optionRegForThirdQ->option}}">{{$optionRegForThirdQ->option}}
      @endforeach
    @endif
    <input type="submit" name="" id="">
    <div class="alert alert-danger" style="    color: #a94442;background-color: #f2dede;border-color: #ebccd1;width: 24%;margin-left: 493px;margin-top: 0px;">
      <ul>
        @if($errors->any())
        @foreach($errors->all() as $err)
          <li>{{$err}}</li>
        @endforeach
        @endif
        </ul>
    </div>
</form>
@if(isset($yoursphereInterting))
<div>
{{$yoursphereInterting}}

</div>
@endif

@endsection

@extends('layout')
@section('content')
 <h1>The Questions And Answers  <span>{{$userName}}</span></h1>
 @if($answersThisUserCount!==0)
@foreach($answersThisUser as $answerThisUser)
    <?php
        $question=DB::table('questions')->where(['id'=>$answerThisUser->question_id])->first();
    ?>
   <h3>
   <span>{{$question->id}}.</span>
   {{$question->question}}:</h3>
    @if($answerThisUser->answer!=='')
    <span><< The Answer: >></span> {{$answerThisUser->answer}}
    @endif
    @if($answerThisUser->option_id==0||$answerThisUser->option_id==null)
 <h5><< The Answer: >>Earth sphere</h5>

    @else
        <?php
    $optionCount=DB::table('options_question')->where(['id'=>$answerThisUser->option_id])->count();
    $option=DB::table('options_question')->where(['id'=>$answerThisUser->option_id])->first();
    ?>
    <h5><< The Answer: >>{{$option->option}}</h5>
   
    @endif
@endforeach
@else 

<div class="alert alert-info">
    there is no answers for this user until now , you can waiting his answers
</div>
@endif
<a href="{{url('/admin/activate-account-user/'.$user->email)}}">Activate This User</a>
<a href="{{url('/admin/deactivate-account-user/'.$user->email)}}">DeActivate This User</a>
@endsection
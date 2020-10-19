
@extends ("layout")
@section("content")
@if($answersSurveyCount==0)
<div class="alert alert-info" role="alert">
    <h4 >there is no answers on this survey    until now </h4>
    </div>
@else
    @foreach($answersSurvey as $answerSurvey)
    <?php 
    $survey=DB::table('surveys')->where(['id'=>$answerSurvey->survey_id])->first();
    $user=DB::table('users')->where(['id'=>$answerSurvey->user_id])->first();
    ?>
        User : <span>{{$user->username}}</span>
       <h4>{{$survey->title}}</h4>
       <p>{{$survey->description}}</p>
        <?php 
        $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
        $userAnsweredOnSurvey=$answerSurvey->user_id;?>
      
      @if($userAnsweredOnSurvey==$user->id)
        <a href="{{url('/delete-my-answer-on-survey/'.$survey->sphere_id.'/'.$answerSurvey->survey_id.'/'.$userAnsweredOnSurvey)}}">Delete My Answer on survey</a>
      @endif
    @endforeach
@endif
@endsection

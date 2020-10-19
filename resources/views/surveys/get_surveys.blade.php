
@extends ("layout")
@section("content")

<a href="{{url('/add-survey/'.$sphere_id)}}">add survey</a>
@if($surveysCount==0)
<div class="alert alert-info" role="alert">
    <h4 >there is no surveys in this sphere   until now </h4>
    </div>
@else
    @foreach($surveys as $survey)
        <form method="POST" action="{{url('/answer-survey/'.$survey->id.'/'.$survey->sphere_id)}}" >
            {{csrf_field()}}
            <h1>{{$survey->title}}</h1>
            <h1>{{$survey->description}}</h1>
            <label for="sphere"class="form-label">The Options</label>
            <p>The Options :</p>
            <?php
            $countFirstOption= DB::table('surveys')->where(['first_option'=>$survey->first_option])->count();
            $countSecondOption= DB::table('surveys')->where(['second_option'=>$survey->second_option])->count();
            ?>
            
            The First Option: {{$countFirstOption}}
                <input type="radio" id="male" name="answer_user" value="{{$survey->first_option}}">
                <label for="answer_user">{{$survey->first_option}}</label><br>
                The Second Option: {{$countSecondOption}}
                <input type="radio" id="female" name="answer_user" value="{{$survey->second_option}}">
                <label for="answer_user">{{$survey->second_option}}</label><br>
            <button type="submit">Send</button>
        </form>
        
        <?php 
        $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
        $userCreatedSurvey=$survey->user_id;?>
        @if($userCreatedSurvey==$user->id)
            <a href="{{url('/delete-my-survey/'.$survey->sphere_id.'/'.$survey->id)}}">Delete My Survey</a>
        @endif
        <a href="{{url('/get-answers-survey/'.$survey->sphere_id.'/'.$survey->id)}}">Answers This survey</a>

    @endforeach
@endif
@endsection

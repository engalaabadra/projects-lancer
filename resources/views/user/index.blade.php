
@extends ("layout")
@section("content")
<div class="loader" id="load_screen">
    <div class="lds-ring">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
  <div class="modal-box">
    <div class="main">
      <button class="close">X</button>
  
       <!-- Sing in  Form -->
       <section class="sign-in open">
        <div class="signin-content"> 
          <!--<div class="signin-image">
                          <figure><img src="images/signin-image.jpg" alt="sing up image"></figure>
                          <a href="javascript:void(0)" class="signup-image-link">Create an account</a>
                      </div>-->
          
          <div class="signin-form">
            <h2 class="form-title">Sign In</h2>
            <form method="POST" class="register-form" id="login-form">
              <div class="form-group"> 
                <!--<label for="your_name"><i class="zmdi zmdi-account material-icons-name"></i></label>-->
                <input type="text" name="your_name" id="your_name" placeholder="Your Name"/>
              </div>
              <div class="form-group"> 
                <!--<label for="your_pass"><i class="zmdi zmdi-lock"></i></label>-->
                <input type="password" name="your_pass" id="your_pass" placeholder="Password"/>
              </div>
              <div class="form-group">
                <input type="checkbox" name="remember-me" id="remember-me" class="agree-term" />
                <label for="remember-me" class="label-agree-term"><span><span></span></span>Remember me</label>
              </div>
              <div class="form-group form-button">
                <input type="submit" name="signin" id="signin" class="form-submit" value="Log in"/>
                
              </div>
              <div class="form-group">
                <div class="social-login">
                  <div class="social-label">Or login with</div>
                  <ul class="socials">
                      <li><a href="#"><i class="display-flex-center zmdi zmdi-facebook"></i></a></li>
                      <li><a href="#"><i class="display-flex-center zmdi zmdi-twitter"></i></a></li>
                      <li><a href="#"><i class="display-flex-center zmdi zmdi-google"></i></a></li>
                  </ul>
                </div>
              </div>
              <a href="javascript:void(0)" class="signup-image-link mobile">Create an account</a> 
            </form>
           
          </div>
        </div>
      </section>
  
      <!-- Sign up form -->
      <section class="signup">
        <div class="signup-content">
          <div class="signup-form">
            <h2 class="form-title">Sign up</h2>
            <form method="POST" class="register-form" id="register-form">
              <div class="form-group"> 
                <!--<label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>-->
                <input type="text" name="name" id="name" placeholder="Your Name"/>
              </div>
              <div class="form-group"> 
                <!--<label for="email"><i class="zmdi zmdi-email"></i></label>-->
                <input type="email" name="email" id="email" placeholder="Your Email"/>
              </div>
              <div class="form-group"> 
                <!--<label for="pass"><i class="zmdi zmdi-lock"></i></label>-->
                <input type="password" name="pass" id="pass" placeholder="Password"/>
              </div>
              <div class="form-group"> 
                <!--<label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>-->
                <input type="password" name="re_pass" id="re_pass" placeholder="Repeat your password"/>
              </div>
              <div class="form-group">
                <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree all statements in <a href="#" class="term-service">Terms of service</a></label>
              </div>
             
              <div class="form-group form-button">
                <input type="submit" name="signup" id="signup" class="form-submit" value="Register"/>
                <a href="javascript:void(0)" class="signup-image-link mobile">Sign In</a> </div>
            </form>
          </div>
          <!--<div class="signup-image">
                          <figure><img src="images/signup-image.jpg" alt="sing up image"></figure>
                          <a href="javascript:void(0)" class="signup-image-link">I am already member</a>
                      </div>--> 
        </div>
      </section>
     
    </div>
  </div>
  <div class="cover-back">
    <div class="logo-back"> <span class="first"></span> <span class="second"></span> <span class="third"></span> <span class="fourth"></span> <span class="five"></span>
      <div class="logo-content">
        <div>
          <h1>HumaniT</h1>
          <h2>The Way The World Ought To Be</h2>
        </div>
      </div>
    </div>
    <div class="carosel-back">
      <div class="scene">
        <div class="carousel">
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
          <div class="carousel__cell"><img src="{{asset('images/1-1-1.png')}}" alt=""></div>
        </div>
      </div>
      <!--<p style="text-align: center; position: relative; z-index: 2;">
                  <button class="previous-button">Previous</button>
                  <button class="next-button">Next</button>
                </p>-->
      
      <div class="ft-name">We’re not Just Talking</div>
    </div>
    <button class="click_button">Explore</button>
  </div>

@endsection
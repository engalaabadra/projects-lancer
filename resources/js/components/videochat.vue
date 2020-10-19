<template>
    <div>
        <h1>
        video call <br> <small >powered by agora</small>
        </h1>
        <h4>My Feed:</h4>
        <div id="me"></div>

            <h4>Remote Feeds:</h4>
        <div id="remote-container">
            
        </div>

        <h4>canvas Feeds:</h4>
        <div id="canvas-container">
            
        </div>
    </div>
</template>
<script>
export default {
mounted(){
      
      let remoteContainer=document.getElementById('remote-container');
      let canvasContainer=document.getElementById('canvas-container');
      function addVideoStream(streamId){//create div for stream and put it in div the first container
          let streamDiv=document.createElement('div');//create div for stream 
          streamDiv.id=streamId;//put in id this div stream : id stream from parfameter
          streamDiv.style.transform='rotateY(180deg)';
          remoteContainer.appendChild(streamDiv);//put this div stream in first container
      }
      function  removeVideoStream(evt){//remove this div stream
          let stream=evt.stream;
          stream.stop();
          let remDiv=document.getElementById(stream.getId());
          remDiv.parentNode.removeChild(remDiv);
          console.log('resuly removed'+ stream.getId());
      }
      
      function addCanvas(streamId){//create div for canvas and put it in div the second container
          let video=document.getElementById(`video${streamId}`);
          let canvas=document.createElement('canvas');
          canvasContainer.appendChild(canvas);
          let ctx=canvas.getContext('2d');
          video.addEventListener('play',function(){//this div video here event on this div
              var $this=this;
              (function loop(){
                  if(!$this.paused&&$this.ended){//if not pused and not stop
                      if($this.width!==canvas.width){
                          canvas.width=video.videoWidth;//
                          canvas.hight=video.videoHight;
                      }
                      ctx.drawImage($this,0,0);
                      setTimeout(loop,1000/3);
                  }
              })()
          },0)
      }
      
      // functinalties
      //1. init client
      let client=AgoraRTC.createClient({
          mode:'live',
          codec:'h264'
      });
      client.init('01a08fd5783d47dd81986121c2ecbfel',()=>console.log('client init ....', ''))
      //2. join into specific channel
      client.join(null,'agora-demo',null,(uid)=>{
          //create stream
          let localStream= AgoraRTC.createStream({
              streamID:uid,
              audio:false,
              video:true,
              screen:false
          });
          

        //init stream
          localStream.init(function(){
              localStream.play('me');
              client.publish(localStream.handleFail);
              //add stream into channel
              client.on('stream-added',function(evt){
                  client.subscribe(evt.stream.handleFail);
              })
              client.on('stream-subscribed',function(evt){
                  let stream=evt.stream;
                  addVideoStream(stream.getId());
                  stream.play(stream.getId());
                  addCanvas(stream.getId())
              })

              client.on('stream-removed',removeVideoStream);
          },handleFail)
      })

}
}
</script>


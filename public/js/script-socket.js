const socket=io('/');
const videoGrid=document.getElementById('video-grid')
// socket.emit('join-room',ROOM_ID,10)

socket.on('user-connected',userId=>{
    console.log('user connected', userId)
})

const myPeer=new Peer(undefined, {
    host:'/',
    post:'3001'
})

const myVideo =document.getElementById('video')
myVideo.muted=true
navigator.mediaDevices.getUserMedia({//to send into other people
    video:true,
    audio:true
}).then(stream=>{
//when reach result (when sending into other people succefully )
    //now will show for them -> the video
    addVideoStream(myVideo,stream)//now will be camera permission to open it
    /////////////////////////////
    myPeer.on('call',call=>{
        call.answer(stream)
        const video=document.createElement('video')
        call.on('stream',userVideoStream=>{
            addVideoStream(video,userVideoStream)
        })
    })
    //these recive from events (on ) from another file
    //to reach this video into other users that joined in this room , this from : through the socket
    socket.on('user-connected',userId=>{
        connectToNewUser(userId,stream)
    })
})

socket.on('user-disconnected',userId=>{
    // console.log(userId)//to logout user from room chat
   if(peers[userId]) peers[userId].close()
})
myPeer.on('open',id=>{
socket.emit('join-room',ROOM_ID,id)
    
})

function addVideoStream(video,stream){
    video.srcObject=stream //to open video
    video.addEventListener('loadedmetadata',()=>{//to lisner in this video
        video.play();//to operate video
    })      
    //put this video in a div
    videoGrid.append(video)
}

function connectToNewUser(userId,stream){
    const call= myPeer.call(userId,stream)//this to call user that has id to this room
    const video=document.createElement('video')
    call.on('stream',userVideoStream=>{
        addVideoStream(video,userVideoStream)//list videos

    })
    call.on('close',()=>{
        video.remove()
    })

    peers[userId]=call
}
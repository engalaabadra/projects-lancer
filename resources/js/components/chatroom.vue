<template>
    <div>

        <div class="container">
            <div class="row" id="app">
                <div class="offset-4 col-4 offset-sm-1 col-sm-10">
                    <li class="list-group-item active">
                        Chat room

                        <span class="btn btn-warning btn-sm"> {{sphereName}}</span>
                    </li>
                    <div class="badge badge-pill badge-primary">
                        {{ typing }}
                    </div>

                    <ul class="list-group" v-chat-scroll>
                        <message
                            v-for="value in chat.message"
                            :key="value.id"   :user="value.user.username" 
                        >
                        
                            {{ value.body }}
                        </message>
                    </ul>
                    <input
                        type="text"
                        class="form-control"
                        placeholder="write your message"
                        v-model="message"
                        @keyup.enter="send"
                    />
                    <br />

                </div>
            </div>
        </div>
    </div>
</template>
<script>
import message from "./message.vue";

export default {
    components: {
        message
    },
    data() {
        return {
            typing:'',
            numUsers:0,
            ErrorBool: false,
            Errors: [],
            ErrorMessage: {
                "500": "Occured Error in server side to show Result Data ! Try again",
                "404": "Somthing Not Found In Result Data ! Try again",
                "401": "You UnAuthorized to see Result Data ! Login ,pls",
                "405": "Somthing Not Allow to show Result Data ! Try again",
                "419": "Somthing Unknown In Result Data ! Try again",
                "No Data In Your Entering": "There is No Data in Your Entering to show it",
                "No Data To Show It": "There is No Data in Something in Show The Result",
                "No Data Tasks": "There is No Tasks For This User to  Show it",
                "No Data Events": "There is No Event For This User to  Show it",
                "Else": "Occured Somthing Error  to show Result Data ! Try again"
            },
            sphereName:'',
            sphereId:'',
            message: "",
            memberName:'',
            numberOfUsers: 0,
            typing:'',
            chat: {
                message: [],
                user: [],
                color: [],
                time: []
            },
            watch: {
                message() {
                    Echo.channel("chat").whisper("typing", {
                        name: this.message
                    });
                }
            }
        };
    },
    methods: {
                getError(id) {
            if (this.ErrorMessage.hasOwnProperty(id))
                return this.ErrorMessage[id];

        },
                callErrorMessage(statusErr) {
            if (
                statusErr == 404 ||
                statusErr == 401 ||
                statusErr == 500 ||
                statusErr == 419 ||
                statusErr == 405
            ) {
            this.Errors = [];

                (this.ErrorBool = true),
                this.Errors.push(
                        statusErr
                    ),
                    () =>
                    setTimeout(() => {
                        this.ErrorBool = false;
                    }, 3000);
            } else {
            this.Errors = [];

                (this.ErrorBool = true),
                this.Errors.push("Else"),
                    () =>
                    setTimeout(() => {
                        this.ErrorBool = false;
                    }, 3000);
            }
        },
        send() {
            if (this.message.length !== 0) {
                this.chat.color.push("success");
                this.chat.time.push(this.getTime());
                      axios
                    .post("/send-messages-user-sphere", {
                        message: this.message,
                        sphereId: this.sphereId,
                        userId: this.userId
                    })
                    .then(response => {
                        this.chat.message.push(response.data);
                        this.message = "";
                axios
            .get(
                `http://127.0.0.1:8000/get-all-messages-sphere/${this.sphereId}`
            )

            .then(response => {                
               this.chat.message = response.data.message;
        
            }).catch(err => this.callErrorMessage(err.response.status));
                    })
                    .catch(err => this.callErrorMessage(err.response.status));
            }
        },
        getTime() {
            let time = new Date();
            return time.getHours() + ":" + time.getMinutes();
        },
        getOldMessages() {
            axios
                .post("/getOldMesaage")
                .then(response => {
                    if (response.data != "") {
                        this.chat = response.data;
                    }
                })
                .catch(err => this.callErrorMessage(err.response.status));
        }
    },
    mounted() {
        this.sphereName = this.$attrs.spherename;
        this.userId = this.$attrs.userid;
        this.sphereId = this.$attrs.sphereid;
        axios
            .get(
                `http://127.0.0.1:8000/get-all-messages-sphere/${this.sphereId}`
            )

            .then(response => {
               this.chat.message = response.data.message;
        
            });

        //recive a message
        Echo.channel("chat")
            .listen("ChatEvent", e => {
                console.log('new message');
                this.chat.message.push(e.message);
                this.chat.color.push("warning");
              axios
            .get(
                `http://127.0.0.1:8000/get-all-messages-sphere/${this.sphereId}`
            )

            .then(response => {                
               this.chat.message = response.data.message;
        
            }).catch(err => this.callErrorMessage(err.response.status));
             

            })
            .listenForWhisper('typing',(e)=>{
                console.log('jjhgg')
                if(e.name!=''){
                    this.typing='typing ...'
                    console.log('typing', e.name)
                }else{
                    this.typing=''

                    console.log('');

                }
            })

            Echo.join(`channel-chat`)
                .here((user)=>{
                    this.numUsers=users.length;
                    console.log('users', users)
                })
                .joining((user)=>{
                    this.numUsers+=1;
                    this.$toaster.success(user+'is joined in room')

                    console.log('username', user.name)
                })
                .leaving((user)=>{
                    this.numUsers-=1;
                    this.$toaster.success(user+'is leaved in room')

                    console.log('username', user.name)
                })




                
            
    }
};
</script>

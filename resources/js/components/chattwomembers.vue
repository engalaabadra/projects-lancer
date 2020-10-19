<template>
<div>
    <div v-if="ErrorBool == true">
        <div v-if="Errors">
            <div class="alert alert-danger" v-for="Error in Errors" :key="Error">
                {{ getError(Error) }}
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row" id="app">
            <div class="offset-4 col-4 offset-sm-1 col-sm-10">
                <li class="list-group-item active">
                    Chat with
                    <span class="btn btn-warning btn-sm">{{memberName}}</span>
                </li>
                <ul class="list-group" v-chat-scroll>

                    <message v-for="value in chat.message" :key="value.index" :time="chat.time">
                        {{ value.body }}
                    </message>
                </ul>
                <input type="text" class="form-control" placeholder="write message" v-model="message" @keyup.enter="send" v-on:keydown="typeingfunc" />
                <br />
            </div>
        </div>
    </div>
</div>
</template>

<script>
import message from "./message.vue";

export default {
    props: ["color", "user", "time"],
    components: {
        message
    },
    data() {
        return {

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
            memberName: "",
            roomId: "",
            memberId: "",
            userId: "",
            message: "",
            numberOfUsers: 0,
            typing: '',
            chat: {
                message: [],
                messageRecieved: [],
                user: [],
                color: [],
                time: []
            },
        };
    },

    methods: {
        
        typeingfunc(){

            setTimeout( () => {
               axios.post(`/typeing-event/`).then(
                   res => {

                   }
               ).catch(
                   err => {

                   }
               );
                
            }, 300);
            
         },
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
            this.roomId = this.$attrs.roomid;
            this.userId = this.$attrs.userid;
            this.memberId = this.$attrs.memberid;
            this.memberName = this.$attrs.membername;

            if (this.message.length !== 0) {
                this.chat.color.push("success");
                this.chat.time.push(this.getTime());
                axios
                    .post("/send-messages-user-member", {
                        message: this.message,
                        memberId: this.memberId,
                        userId: this.userId
                    })
                    .then(response => {
                        this.chat.message.push(response.data);
                        this.message = "";
                        axios
                            .get(
                                `http://127.0.0.1:8000/get-all-messages-individual/user/${this.userId}/member/${this.memberId}`
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
        },
        deleteSession() {
            axios
                .post("/deleteSession")
                .then(response => {
                    this.$toaster.success("chat history is deleted");
                })
                .catch(err => this.callErrorMessage(err.response.status));
        }
    },
    mounted() {
        this.roomId = this.$attrs.roomid;
        this.userId = this.$attrs.userid;
        this.memberId = this.$attrs.memberid;
        //get messages 
        axios
            .get(
                `http://127.0.0.1:8000/get-all-messages-individual/user/${this.userId}/member/${this.memberId}`
            )

            .then(response => {
                this.chat.message = response.data.message;

            }).catch(err => this.callErrorMessage(err.response.status));

        //recive a message
        Echo.channel("AppRoom." + this.userId)
            .listen("MessageDelivered", e => {
                this.chat.message.push(e.message);
                axios
                    .get(
                        `http://127.0.0.1:8000/get-all-messages-individual/user/${this.userId}/member/${this.memberId}`
                    )

                    .then(response => {
                        this.chat.message = response.data.message;

                    }).catch(err => this.callErrorMessage(err.response.status));

            });
    }
};
</script>

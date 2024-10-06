<template>
<div style="  width: 190%; margin-left: 10px; margin-top: 22px;">
    <nav aria-label="Page navigation example">

        <ul class="pagination">
            <li class="page-item" v-bind:class="[{disabled:!pagination.prev_page_url}]"><a class="page-link" href="#" @click="fetchComments(pagination.prev_page_url)">Previous</a></li>
            <li class="page-item disabled">
                <a href="#" class="page-link text-dark">Page {{pagination.current_page}} of {{pagination.last_page}}</a>

            </li>
            <li class="page-item" v-bind:class="[{disabled:!pagination.next_page_url}]"><a class="page-link" href="#" @click="fetchComments(pagination.next_page_url)">Next</a></li>

        </ul>
    </nav>
    <vc-calendar :attributes="attributes" />

    <div v-if="ErrorBool == true">
        <div v-if="Errors">
            <div class="alert alert-danger" v-for="Error in Errors" :key="Error">
                {{ getError(Error) }}
            </div>
        </div>
    </div>

    <div>
        <div v-if="Success">
            <div class="alert alert-info" v-for="Succ in Success" :key="Succ">
                {{ getSuccessMessages(Succ) }}
            </div>
        </div>
    </div>

    <form @submit.prevent="fetchaddCommentOnConversation()" class="sglefeedsec" style="height: 189px;">
        <div class="form-group" style="margin-top: 21px;">
            <textarea type="text" class="form-control" placeholder="Write a message" v-model="comment.body">
                </textarea>
        </div>
        <button type="submit" class="btn btn-info btn-block" style="margin-bottom: 55px;">
            save
        </button>
    </form>

    <hr />
    The Comments
    <hr />

    <div class="card card-body mb-2" v-for="(comment) in comments_conversation" v-bind:key="comment.id">
        <span v-if="comment.user != null" style="margin-left: 54px; margin-top: 26px;">{{ comment.user.username }}</span>
        <span style="margin-left: 421px; margin-top: -23px;">{{
                comment.created_at
            }}</span>
        <p style="margin-top: 34px; margin-left: 48px;">
            {{ comment.body }}
        </p>
        <div v-if="comment.user_id == userId">

            <button @click="editComment(comment)" class="btn btn-success " style="width: 9%; margin-left: 471px;">
                edit
            </button>
            <button @click="deleteComment(comment.id)" class="btn btn-danger" style="width: 10%; margin-left: 378px; margin-top: -34px;">
                delete
            </button>
        </div>
        <div v-show="reply_visible !== comment.id">
            <hr />
            <button @click="replyVisible(comment.id)" class="btn btn-info" style="width: 13%;     margin-left: 382px; margin-top: -11px;">
                add reply
            </button>
        </div>
        <div v-show="reply_visible === comment.id">
            <form @submit.prevent="
                        fetchaddReplyCommentOnConversation(comment.id)
                    ">
                <div class="form-group" style="   margin-top: 40px;">
                    <textarea type="text" class="form-control" placeholder="Body" :id="'body-' + comment.id" name="body"></textarea>
                </div>
                <button class="btn btn-info" style="width: 9%;     margin-left: 410px; margin-top: -3px;" placeholder="Write Your Comment ">
                    Save
                </button>
            </form>
        </div>
        <div v-for="reply in comment.replies" v-bind:key="reply.id">
            <span v-if="reply.user">{{ reply.user.username }}</span>
            <p :id="'old-' + comment.id + '-' + reply.id">

                {{ reply.body }}

            </p>

            <div v-if="reply.user_id == userId">

                <button @click="editReplyComment(reply, comment)" class="btn btn-success " style="width: 13%; margin-left: 324px; margin-top: 15px;"></button>
                <button @click="deleteReplyComment(comment, reply.id)" class="btn btn-danger" style="width: 15%;     margin-left: 409px; margin-top: -55px;">
                    delete reply
                </button>
            </div>
        </div>
    </div>
    <div v-for="task in tasks" :key="task.id">
        <span>enddd: {{ task.end_task }}</span>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            pagination: {},
            countCommentsConv: 0,
            attributes: [{
                key: "today",
                highlight: true,
                dot: true,
                bar: true,
                content: "red",
                dates: new Date(),
                excludeDates: null,
                order: 2
            }],
            ErrorBool: false,
            SuccessBool: false,
            Errors: [],
            Success: [],
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

            SuccessMessage: {
                "Add": "Added Your Data Succcessfully",
                "Edit": "Updated Your Data Succcessfully",
                "Delete": "Deleted Your Data Succcessfully",
                "Mention": "Sent Your Mention into this user Successfully",
                "Else": "Your Proccessing Succesfully"
            },
            users: [],
            edit_comment: false,
            edit_reply_comment: false,
            userId: "",
            posts: [],
            spheres: [],
            sphereId: "",
            conversationId: "",
            details_conversation: [],
            addCommentOnConversation: "",
            postsMembersInMyProfile: [],
            comments_conversation: [],
            replies: [],
            spheres_founded: [],
            tasks_accepted: [],
            posts_created: [],
            conversations_created: [],
            followers_user: [],
            followers_user_to_see_part_profile: [],
            post: {
                id: "",
                title: "",
                body: ""
            },
            comment: {
                id: "",
                title: "",
                body: ""
            },
            reply: {
                id: "",
                title: "",
                body: ""
            },
            sphere: "",
            post_id: "",
            pagination: {},
            edit: false,
            reply_visible: null,
            tasks: []
        };
    },
    created() {
        // setInterval(() => {
        if (this.countCommentsConv != 0) {

        }
        this.addViewUsers();
        this.fetchComments();
        // }, 2000);
    },
    methods: {
        getError(id) {
            if (this.ErrorMessage.hasOwnProperty(id))
                return this.ErrorMessage[id];

        },
        getSuccessMessages(id) {
            if (this.SuccessMessage.hasOwnProperty(id))
                return this.SuccessMessage[id];

        },
        callSuccessMessage(msg) {

            this.Success = [];
            this.Success.push(msg);

        },
        noDataInYourEntering() {
            this.Errors = [];

            (this.ErrorBool = true),
            this.Errors.push("No Data In Your Entering"),
                () =>
                setTimeout(() => {
                    this.ErrorBool = false;
                }, 3000);
        },
        noDataInSomethingResult() {
            this.Errors = [];

            (this.ErrorBool = true),
            this.Errors.push("No Data To Show It"),
                () =>
                setTimeout(() => {
                    this.ErrorBool = false;
                }, 3000);
        },
        noTasksForThisUser() {
            this.Errors = [];

            (this.ErrorBool = true),
            this.Errors.push("No Data To Show It"),
                () =>
                setTimeout(() => {
                    this.ErrorBool = false;
                }, 3000);
        },
        noEventsForThisUser() {
            this.Errors = [];

            (this.ErrorBool = true),
            this.Errors.push("No Data To Show It"),
                () =>
                setTimeout(() => {
                    this.ErrorBool = false;
                }, 3000);
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
        addViewUsers() {
            this.conversationId = this.$attrs.conversation_id;
            this.sphereId = this.$attrs.sphere_id;
            axios
                .post(
                    `http://127.0.0.1:8000/add-user-view-into-conversation/${this.conversationId}/sphere/${this.sphereId}`
                )
                .then(result => {})
                .catch(err => this.callErrorMessage(err.response.status));
        },
        replyVisible(comment_id) {
            this.reply_visible = comment_id;
        },
        fetchComments(page_url) {
            this.conversationId = this.$attrs.conversation_id;
            this.sphereId = this.$attrs.sphere_id;
            page_url = page_url || `http://127.0.0.1:8000/get-comments/conversation/${this.conversationId}/sphere/${this.sphereId}`;

            axios(
                    page_url
                )
                .then(res => {
                    if (res.data.status == 404) {
                        this.noDataInSomethingResult();
                    } else if (res.data.status == 401) {
                        this.callErrorMessage(res.data.status)
                    } else {
                        if (res.data.data !== "") {
                            this.makePagination(res.data.meta, res.data.links)

                            this.comments_conversation = res.data.data;
                        } else {
                            this.noDataInSomethingResult();
                        }
                    }
                })
                .catch(err => this.callErrorMessage(err.response.status));
        },
        makePagination(meta, links) {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev
            };
            this.pagination = pagination;
        },
        editComment(comment) {
            this.edit_comment = true;
            this.comment.id = comment.id;
            this.comment.body = comment.body;
        },
        editReplyComment(reply, comment) {
            this.reply.id = reply.id;
            this.reply_visible = comment.id;
            this.edit_reply_comment = true;
            document.getElementById(
                "body-" + comment.id
            ).value = document.getElementById(
                "old-" + comment.id + "-" + reply.id
            ).innerText;
        },
        fetchaddCommentOnConversation() {
            if (this.edit_comment == false) {
                this.conversationId = this.$attrs.conversation_id;
                this.sphere_id = this.$attrs.sphere_id;
                this.userId = this.$attrs.user_id;
                //add
                axios
                    .post(
                        `http://127.0.0.1:8000/add-comment-on-conversation/${this.sphere_id}/${this.conversationId}/${this.userId}`,

                        {
                            body: this.comment.body
                        }
                    )
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            if (res.data.data.body != null) {
                                this.comments_conversation.push(res.data);
                                this.fetchComments();
                                setTimeout(() => {
                                    this.callSuccessMessage('Add');
                                }, 500)

                            } else {
                                this.noDataInSomethingResult();
                            }
                        }

                    }).catch(err => this.callErrorMessage(err.response.status));
            } else {
                this.conversationId = this.$attrs.conversation_id;
                this.sphereId = this.$attrs.sphere_id;
                this.userId = this.$attrs.user_id;

                axios
                    .put(
                        `http://127.0.0.1:8000/update-comment-conversation/${this.comment.id}/${this.sphereId}/${this.conversationId}/${this.userId}`, {
                            body: this.comment.body
                        }
                    )
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            if (res.data.data.body != null) {
                                this.comments_conversation.push(res.data);
                                this.edit_comment = false;
                                this.fetchComments();
                                this.callSuccessMessage('Edit');

                            } else {
                                this.noDataInYourEntering();
                            }
                        }
                    })
                    .catch(err => this.callErrorMessage(err.response.status));

            }
        },
        deleteComment(id) {
            if (confirm("Are you sure?")) {
                this.sphereId = this.$attrs.sphere_id;
                this.conversation_id = this.$attrs.conversation_id;
                this.userId = this.$attrs.user_id;

                axios
                    .delete(
                        `/delete-comment-conversation/${id}/${this.sphereId}/${this.conversation_id}/${this.userId}`
                    )
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            alert("post Removed");
                            this.fetchComments();
                            this.callSuccessMessage('Delete');
                        }
                    })
                    .catch(err => this.callErrorMessage(err.response.status));

            }
        },

        fetchaddReplyCommentOnConversation(comment_id) {
            if (this.edit_reply_comment == false) {
                this.conversationId = this.$attrs.conversation_id;
                this.sphere_id = this.$attrs.sphere_id;
                this.userId = this.$attrs.user_id;
                //add
                axios
                    .post(
                        `http://127.0.0.1:8000/add-reply-comment-on-conversation/${this.sphere_id}/${this.conversationId}/comment/${comment_id}/user/${this.userId}`, {
                            body: document.getElementById("body-" + comment_id).value
                        }
                    )
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            if (res.data.data.body != null) {
                                this.comments_conversation.push(res.data.data);
                                this.callSuccessMessage('Add');
                                this.fetchComments();
                            } else {
                                this.noDataInYourEntering()
                            }
                        }
                    })
                    .catch(err => this.callErrorMessage(err.response.status));

            } else {
                this.conversationId = this.$attrs.conversation_id;
                this.sphereId = this.$attrs.sphere_id;
                this.userId = this.$attrs.user_id;

                axios
                    .put(
                        `http://127.0.0.1:8000/update-reply-comment-conversation/${comment_id}/${this.sphereId}/${this.conversationId}/${this.reply.id}/${this.userId}`, {
                            body: document.getElementById("body-" + comment_id).value
                        }
                    )
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {

                            if (res.data.data.body != null) {
                                setTimeout(() => {
                                    this.edit_reply_comment = false;
                                    this.comments_conversation.push(
                                        res.data.data
                                    );
                                    this.callSuccessMessage('Edit');
                                    this.fetchComments();
                                }, 200);
                            } else {
                                this.noDataInYourEntering()
                            }
                        }
                    })
                    .catch(err => this.callErrorMessage(err.response.status));

            }
        },
        deleteReplyComment(comment, reply_id) {
            if (confirm("Are you sure?")) {
                this.sphereId = this.$attrs.sphere_id;
                this.conversation_id = this.$attrs.conversation_id;
                this.userId = this.$attrs.user_id;

                axios
                    .delete(
                        `/delete-reply-comment-conversation/${comment.id}/${this.sphereId}/${this.conversation_id}/${reply_id}/${this.userId}`
                    )
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            alert("post Removed");
                            this.callSuccessMessage('Delete');
                            this.fetchComments();
                        }
                    })
                    .catch(err => this.callErrorMessage(err.response.status));

            }
        }
    },
    mounted() {
        this.userId = this.$attrs.user_id;
        this.sphereId = this.$attrs.sphere_id;
        this.conversationId = this.$attrs.conversation_id;
        axios("/get-data-tasks-user-for-calender/").then(res => {
                if (res.data.status == 404) {
                    this.noDataInSomethingResult();
                } else if (res.data.status == 401) {
                    this.callErrorMessage(res.data.status)
                } else {
                    var dataTasks = [];
                    dataTasks = res.data.message;
                    if (dataTasks !== []) {
                        this.todos = res.data.message;
                        dataTasks.forEach(d => {
                            this.attributes.push({
                                highlight: {
                                    color: "red",
                                    fillMode: "light"
                                },
                                dates: [d.start_task, d.end_task],
                                popover: {
                                    label: `name : ${d.name_task}: desc : ${d.description_task} :start task:${d.start_task} :end task:${d.end_task}`
                                }
                            })
                        })
                    } else {
                        this.noTasksForThisUser();
                    }
                }
            })
            .catch(err => this.callErrorMessage(err.response.status));

        axios("/get-data-events-user-for-calender/").then(res => {
                if (res.data.status == 404) {
                    this.noDataInSomethingResult();
                } else if (res.data.status == 401) {
                    this.callErrorMessage(res.data.status)
                } else {
                    var dataEvents = [];
                    dataEvents = res.data.message;
                    this.todos = res.data.message;
                    if (dataEvents !== []) {
                        dataEvents.forEach(d => {
                            this.attributes.push({
                                highlight: {
                                    color: "blue",
                                    fillMode: "light"
                                },
                                dates: [
                                    d.event_time
                                ],
                                popover: {
                                    label: `name : ${d.title}: desc : ${d.description} :time:${d.event_time}`
                                }
                            })
                        })
                    } else {
                        this.noEventsForThisUser();
                    }
                }
            })
            .catch(err => this.callErrorMessage(err.response.status));
    }
};
</script>

<template>
<div>
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
    <h5> post</h5>
    body: {{ postname }}
    <form @submit.prevent="addCommentStatus(post_id)">
        {{ comment.body }}
        <div class="form-group">
            <textarea type="text" class="form-control" placeholder="Body" id="body" name="body" v-model="comment.body">
                </textarea>
        </div>
        <button class="btn btn-success" style="    width: 26%;margin-left: 1003px;margin-top: -37px;">
            Save
        </button>
    </form>
    <div class="card card-body mb-2" v-for="comment in comments" v-bind:key="comment.id">
        the comment : {{ comment.body }}

        <button @click="showMention(comment.id, post.id)" :id="comment.id + '-' + post.id">
            show the mention in the comment
        </button>

        <div v-if="ViewMention === comment.id + '-' + post.id">
            <p> {{ mentionUser }}</p>
        </div>

    </div>
    <button @click="editComment(comment, post)" class="btn btn-warning" style="    width: 26%;margin-left: 592px;margin-top: -37px;">
        edit
    </button>

    <button @click="deleteComment(comment, post)" class="btn btn-danger" style="     width: 26%;margin-left: 245px;margin-top: -2px;">
        delete
    </button>
</div>
</template>

<script>
export default {
    data() {
        return {
            ViewMention: null,
            showWrongAlertNotFound: false,
            showWrongAlertUnknown: false,
            showWrongAlertServer: false,
            showWrongAlertForbidden: false,
            showWrongAlertNotAllow: false,
            mentionUser: "",
            postname: '',
            post: [],
            comments: [],

            comment: {
                id: "",
                body: ""
            },
            sphere: "",
            editCommentStatus: "",
            post_id: "",
            pagination: {},
            edit: false,
            add_comment: false,
            edit_comment: false,
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
            }

        };
    },
    created() {
        this.fetchComments();
        this.getError();
        this.getSuccessMessages();
        this.callErrorMessage();
        this.noDataInYourEntering();
        this.noDataInSomethingResult();
        this.callSuccessMessage();
        this.noTasksForThisUser();
        this.noEventsForThisUser();
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
            this.Errors.push("No Data Tasks"),
                () =>
                setTimeout(() => {
                    this.ErrorBool = false;
                }, 3000);
        },
        noEventsForThisUser() {
            this.Errors = [];

            (this.ErrorBool = true),
            this.Errors.push("No Data Events"),
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
        fetchComments() {
            this.post_id = this.$attrs.post_id;
            this.sphere_id = this.$attrs.sphere_id;
            axios(`http://127.0.0.1:8000/comments-post/${this.post_id}/${user_id}`)

                .then(res => {

                    if (res.data.data !== "") {
                        this.comments = res.message;
                    } else {
                        this.noDataInSomethingResult();
                    }

                })
                .catch(err => {

                    if (err.response) {
                        this.callErrorMessage(err.response.status)
                    } else {
                        this.callErrorMessage('Else')
                    }

                });

        },
        makePagination(meta, links) {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: next_page_url,
                prev_page_url: next_page_url
            };
            this.pagination = pagination;
        },
        deletePost(id) {
            if (confirm("Are you sure?")) {
                var sphereId = this.$attrs.sphere_id;
                axios(`/post/${id}/${sphereId}`, {
                        method: "delete"
                    })
                    .then(response => {
                        if (response) {
                            this.callSuccessMessage('Delete');
                            alert("post Removed");
                            this.fetchComments();
                        }
                    })
                    .catch(err => Exception.handle(err));
            }
        },

        addPost() {
            if (this.edit == false) {
                //add
                var sphereId = this.$attrs.sphere_id;
                var userId = this.$attrs.user_id;
                axios.post(`/add-post-sphere/${sphereId}/${userId}`, {
                        body: this.post
                    })
                    .then(res => {
                        if (res.data.message.body != null) {
                            this.post.body = res.data.message.body;
                            this.callSuccessMessage('Add');
                            this.fetchComments();

                        } else {
                            this.noDataInYourEntering();
                        }
                    })
                    .catch(err => {

                        if (err.response) {
                            this.callErrorMessage(err.response.status)
                        } else {
                            this.callErrorMessage('Else')
                        }

                    });

            } else {
                //update
                var sphereId = this.$attrs.sphere_id;
                var userId = this.$attrs.user_id;
                axios.put(`/edit-post-sphere/${sphereId}/${userId}`, {
                        body: this.post
                    })

                    .then(data => {
                        if (res.data.data.body != null) {
                            this.post.title = "";
                            this.post.body = "";
                            alert("post updated");
                            this.callSuccessMessage('Edit');
                            this.fetchComments();
                        } else {
                            this.noDataInYourEntering()

                        }
                    })
                    .catch(err => {

                        if (err.response) {
                            this.callErrorMessage(err.response.status)
                        } else {
                            this.callErrorMessage('Else')
                        }

                    });
            }
        },

        addCommentStatus(id) {
            if (this.edit_comment == false) {
                this.add_comment = true;
                var userElementt = document.getElementById("userElement");
                this.userId = this.$attrs.user_id;

                //add
                axios.post(`http://127.0.0.1:8000/post-comment/${id}/${this.userId}`, {
                        body: this.comment.body
                    })

                    .then(res => {

                        if (res.data.data.body != null) {
                            this.comments = response.data;
                            this.callSuccessMessage('Add');
                            this.fetchComments();

                        } else {
                            this.noDataInYourEntering()
                        }

                    })
                    .catch(err => {

                        if (err.response) {
                            this.callErrorMessage(err.response.status)
                        } else {
                            this.callErrorMessage('Else')
                        }

                    });

            } else {
                // editComment = (comment, post) => {
                this.userId = this.$attrs.user_id;

                axios.put(
                        `http://127.0.0.1:8000/post-comment/${this.comment.id}/${this.post_id}/${userId}`, {
                            body: this.comment.body

                        }
                    )

                    .then(res => {
                        if (res.data.data.body != null) {
                            this.editCommentStatus = true;
                            this.callSuccessMessage('Edit');

                            this.fetchComments();
                        } else {
                            this.noDataInYourEntering()
                            this.noDataInYourEntering()

                        }

                    })
                    .catch(err => {

                        if (err.response) {
                            this.callErrorMessage(err.response.status)
                        } else {
                            this.callErrorMessage('Else')
                        }

                    });

            }
        },
        showMention(commentId, post_id) {
            this.ViewMention = commentId + "-" + post_id;
            axios
                .get(
                    `http://127.0.0.1:8000/get-user-mention-comment/${commentId}/post/${post_id}/sphere/`
                )
                .then(result => {
                    if (result.data.message != null) {
                        axios
                            .get(
                                `http://127.0.0.1:8000/show-user-email/${result.data.message.user_id}`
                            )
                            .then(result => {
                                this.mentionUser = result.data.message.email;
                            }).catch(err => {

                                if (err.response) {
                                    this.callErrorMessage(err.response.status)
                                } else {
                                    this.callErrorMessage('Else')
                                }

                            });
                    } else {
                        this.mentionUser =
                            "this comment have not mention for any user";
                        // this.noDataInSomethingResult();
                    }
                }).catch(err => {

                    if (err.response) {
                        this.callErrorMessage(err.response.status)
                    } else {
                        this.callErrorMessage('Else')
                    }

                });
        },
        editPost(post) {
            this.edit = true;
            this.post.post_id = post.id;
            this.post.title = post.title;
            this.post.body = post.body;
        },
        deleteComment(comment, post) {
            this.userId = this.$attrs.user_id;

            if (confirm("Are you sure?")) {
                this.post_id = this.$attrs.post_id;
                var sphereId = this.$attrs.sphere_id;
                axios.delete(
                        `/delete-comment/${comment.id}/${this.post_id}/${sphereId}/${this.userId}`
                    )
                    .then(res => {
                        this.callSuccessMessage('Delete');

                        alert("post Removed");

                        this.fetchComments();

                    }).catch(err => {

                        if (err.response) {
                            this.callErrorMessage(err.response.status)
                        } else {
                            this.callErrorMessage('Else')
                        }

                    });
            }
        },
        editComment(comment, post) {
            this.edit_comment = true;
            this.post.id = post.id;
            this.comment.id = comment.id;
            this.comment.body = comment.body;
        }

    },
    mounted() {
        this.postname = this.$attrs.postname;
        this.post_id = this.$attrs.post_id;

    }
};
</script>

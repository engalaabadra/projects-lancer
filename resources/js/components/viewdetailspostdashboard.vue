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
    <div style="width: 100%; margin-left: 96px; margin-top: -1px;overflow-y: scroll; height: 80rem;">
        <h5> post</h5>

        <form @submit.prevent="addCommentStatus(post_id)">
            {{ comment.body }}
            <div class="form-group">
                <textarea type="text" class="form-control" placeholder="Body" id="body" name="body" v-model="comment.body">
                </textarea>
                <input list="browsers" class="col-sm-7 form-control" id="mention" />
                <datalist id="browsers">
                    <option v-for="user in users" :key="user.id" :value="user.email" >{{ user.email }}</option>
                </datalist>
            </div>
            <button class="btn btn-success" style="    width: 26%;margin-left: 1003px;margin-top: -37px;">
                Save
            </button>
        </form>
        The Comments:
        <div class="card card-body mb-2" v-for="comment in comments" v-bind:key="comment.id">
            {{ comment.body }}

            <button @click="showMention(comment.id,comment.post_id)" :id="comment.id + '-' + comment.post_id">show the mention in the comment</button>
            <div v-if="ViewMention === comment.id + '-' + comment.post_id">
                <p>{{mentionUser}}</p>
            </div>
            <div v-if="comment.user_id == userId">
                <button @click="editComment(comment, post)" class="btn btn-warning" style="    width: 26%;margin-left: 592px;margin-top: -37px;">
                    edit
                </button>

                <button @click="deleteComment(comment, post)" class="btn btn-danger" style="     width: 26%;margin-left: 245px;margin-top: -2px;">
                    delete
                </button>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            mentionUser: "",
            postname: '',
            post: [],
            comments: [],
            users: [],
            ViewMention: null,

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
        this.fetchAllUsers();
    },
    methods: {
        storeMentionUser(idUserMention, id, post) {
            axios
                .post(
                    `http://127.0.0.1:8000/store-user-in-mention-table/${idUserMention}/comment/${id}/post/${post}/sphere/0`
                )

                .then(res => {
                    this.callSuccessMessage('Mention');
                })
                .catch(err => {

                    if (err.response) {
                        this.callErrorMessage(err.response.status)
                    } else {
                        this.callErrorMessage('Else')
                    }

                });
        },
        updateMentionUser(idUserMention, id, post) {
            axios
                .post(
                    `http://127.0.0.1:8000/update-user-in-mention-table/${idUserMention}/comment/${id}/post/${post}/sphere/0`
                )

                .then(res => {
                    this.callSuccessMessage('Mention');
                })
                .catch(err => {

                    if (err.response) {
                        this.callErrorMessage(err.response.status)
                    } else {
                        this.callErrorMessage('Else')
                    }

                });
        },
        fetchAllUsers() {
            axios(`/get-all-users/`)
                .then(res => {
                    if (res.data.status == 404) {
                        this.noDataInSomethingResult();
                    } else if (res.data.status == 401) {
                        this.callErrorMessage(res.data.status)
                    } else {
                        if (res.data.message !== null) {
                            this.users = res.data.message;
                        }
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
            (this.ErrorBool = true),
            this.Errors.push("No Data In Your Entering"),
                () =>
                setTimeout(() => {
                    this.ErrorBool = false;
                }, 3000);
        },
        noDataInSomethingResult() {
            (this.ErrorBool = true),
            this.Errors.push("No Data To Show It"),
                () =>
                setTimeout(() => {
                    this.ErrorBool = false;
                }, 3000);
        },
        noTasksForThisUser() {
            (this.ErrorBool = true),
            this.Errors.push("No Data Tasks"),
                () =>
                setTimeout(() => {
                    this.ErrorBool = false;
                }, 3000);
        },
        noEventsForThisUser() {
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
                (this.ErrorBool = true),
                this.Errors.push(
                        statusErr
                    ),
                    () =>
                    setTimeout(() => {
                        this.ErrorBool = false;
                    }, 3000);
            } else {
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
            axios(`http://127.0.0.1:8000/comments-post-dashboard/${this.post_id}/0`)

                .then(res => {
                    if (res.data.status == 404) {
                        this.noDataInSomethingResult();
                    } else if (res.data.status == 401) {
                        this.callErrorMessage(res.data.status)
                    } else {
                        if (res.data.message !== "") {
                            this.comments = res.data.message;
                        } else {
                            this.noDataInSomethingResult();
                        }
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

        addCommentStatus(id) {
            var userElement = document.getElementById("mention");

            var valueUserElement = userElement.value;

            if (this.edit_comment == false) {
                this.add_comment = true;
                var userElementt = document.getElementById("userElement");
                this.userId = this.$attrs.user_id;

                //add
                axios.post(`http://127.0.0.1:8000/post-comment/${id}/0/${this.userId}`, {
                        body: this.comment.body
                    })

                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            if (res.data.data.body != null) {
                                this.comments = res.data;
                                this.fetchComments();
                                if (valueUserElement !== "") {
                                    axios(
                                        `http://127.0.0.1:8000/show-user/${valueUserElement}`
                                    ).then(result => {
                                        if (res.data.status == 404) {
                                            this.noDataInSomethingResult();
                                        } else if (res.data.status == 401) {
                                            this.callErrorMessage(res.data.status)
                                        } else {
                                            this.idUserMention = result.data.message.id;
                                            this.storeMentionUser(this.idUserMention, res.data.data.id, id);
                                            this.callSuccessMessage('Add');
                                        }
                                    }).catch(err => {
                                        if (err.response) {
                                            this.callErrorMessage(err.response.status)
                                        } else {
                                            this.callErrorMessage('Else')

                                        }

                                    })
                                } else {
                                    this.callSuccessMessage('Add');

                                }

                            } else {
                                this.noDataInYourEntering()
                            }
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
                        `http://127.0.0.1:8000/post-comment/${this.comment.id}/${this.post_id}/0/${this.userId}`, {
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

                                this.editCommentStatus = true;
                                this.callSuccessMessage('Edit');

                                this.fetchComments();

                                if (valueUserElement !== "") {
                                    axios(
                                        `http://127.0.0.1:8000/show-user/${valueUserElement}`
                                    ).then(result => {
                                        if (res.data.status == 404) {
                                            this.noDataInSomethingResult();
                                        } else if (res.data.status == 401) {
                                            this.callErrorMessage(res.data.status)
                                        } else {
                                            this.idUserMention =
                                                result.data.message.id;
                                            this.updateMentionUser(this.idUserMention, res.data.data.id, id);
                                            this.callSuccessMessage('Edit');
                                        }
                                    }).catch(err => {
                                        if (err.response) {
                                            this.callErrorMessage(err.response.status)
                                        } else {
                                            this.callErrorMessage('Else')

                                        }

                                    })
                                } else {
                                    this.callSuccessMessage('Add');

                                }
                            } else {
                                this.noDataInYourEntering()

                            }
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
                    if (result.data.status == 404) {
                        this.noDataInSomethingResult();
                    } else if (result.data.status == 401) {
                        this.callErrorMessage(result.data.status)
                    } else {
                        if (result.data.message != null) {
                            axios
                                .get(
                                    `http://127.0.0.1:8000/show-user-email/${result.data.message.user_id}`
                                )
                                .then(result => {
                                    if (result.data.status == 404) {
                                        this.noDataInSomethingResult();
                                    } else if (result.data.status == 401) {
                                        this.callErrorMessage(result.data.status)
                                    } else {
                                        this.mentionUser = result.data.message.email;
                                        this.ViewMention = commentId + "-" + post_id;
                                    }
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
                            this.ViewMention = commentId + "-" + post_id;
                        }
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
                        `/delete-comment/${comment.id}/${this.post_id}/0/${this.userId}`
                    )
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            this.callSuccessMessage('Delete');

                            alert("post Removed");

                            this.fetchComments();
                        }
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
         //   this.post.id = post.id;
        this.post_id = this.$attrs.post_id;

            this.comment.id = comment.id;
            this.comment.body = comment.body;
                        axios
                .get(
                    `http://127.0.0.1:8000/get-user-mention-comment/${comment.id}/post/${this.post_id}/sphere/`
                ).then(res=>{
                    if(res.data.message!=null){
                var userElement = document.getElementById("mention");
                var valueUserElement = userElement.value;
                   res.data.message.user.email;
                 let r= userElement.setAttribute("value",res.data.message.user.email);

                    }

            })
        }

    },
    mounted() {
        this.userId = this.$attrs.user_id;

        this.postname = this.$attrs.postname;
        this.post_id = this.$attrs.post_id;

    }
};
</script>

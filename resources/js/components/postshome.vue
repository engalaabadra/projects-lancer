<template>
<div>

    <nav aria-label="Page navigation example">

        <ul class="pagination">
            <li class="page-item" v-bind:class="[{disabled:!pagination.prev_page_url}]"><a class="page-link" href="#" @click="fetchPosts(pagination.prev_page_url)">Previous</a></li>
            <li class="page-item disabled">
                <a href="#" class="page-link text-dark">Page {{pagination.current_page}} of {{pagination.last_page}}</a>

            </li>
            <li class="page-item" v-bind:class="[{disabled:!pagination.next_page_url}]"><a class="page-link" href="#" @click="fetchPosts(pagination.next_page_url)">Next</a></li>

        </ul>
    </nav>
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
        <vc-calendar :attributes="attributes" />
    <div style="width: 100%; margin-left: 96px; margin-top: -1px;overflow-y: scroll; height: 80rem;">
        <form @submit.prevent="addPostHome()" class="sglefeedsec" style="height: 189px;">
            <div class="form-group" style="margin-top: 21px;">
                <textarea class="form-control" placeholder="What do you have to say about the world alaaaaaaaaaaaaaaaaa ?" v-model="post.body"></textarea>
            </div>
            <button type="submit" class="btn btn-info btn-block" style="margin-bottom: 55px;">
                save
            </button>
        </form>
        <hr />
        The Posts
        <hr />
        <div class="card card-body mb-2" v-for="post in allPostsHome" v-bind:key="post.id">
            <p v-if="post.user">username : {{ post.user.username }}</p>
            <span style="margin-left: 421px;!important; margin-top: -23px;!important;">{{ post.created_at }}</span>
            <p style="margin-top: 34px;">{{ post.body }}</p>
            <div v-if="post.user">
                <div v-if="post.user.id == userId">
                    <button @click="editPost(post)" class="btn btn-success" style=" width: 8%; margin-left: 599px;">
                        edit
                    </button>
                    <button @click="deletePost(post.id)" class="btn btn-danger" style="width: 8%;margin-left: 672px; margin-top: -33px;">
                        delete
                    </button>
                </div>
            </div>
            The Comments:
            <hr />
            <form @submit.prevent="addCommentStatus(post.id, update)">
                <div class="form-group" style="margin-top: 40px;">
                    <textarea type="text" class="form-control" placeholder="Body" :id="'comment-' + post.id" name="body">
                        </textarea>
                    <input list="browsers" class="col-sm-7 form-control" id="mention" />
                    <datalist id="browsers">
                        <option v-for="user in users" :key="user.id" :value="user.email">{{ user.email }}</option>
                    </datalist>
                </div>
                <button class="btn btn-info" style=" width: 15%; margin-top: -3px;" placeholder="Write Your Comment ">
                    Add Comment
                </button>
                <hr />
            </form>
            <div class="card" v-for="comment in post.comments" v-bind:key="comment.id" style="margin-top: 27px;">
                <span style="margin-left: 36px; margin-top: 18px;" v-if="comment.user">{{ comment.user.username }}</span>
                <span style="margin-left: 421px;margin-top: -23px;">{{
                        comment.created_at
                    }}</span>

                <p style="margin-top: 34px; margin-left: 32px; " :id="'old-' + post.id + '-' + comment.id">
                    {{ comment.body }}
                </p>
                <div v-if="comment.user_id == userId">
                    <button @click="editComment(post.id, comment.id)" class="btn btn-warning" style="margin-left: 569px;margin-top: -17px; width: 73px; margin-bottom: -41px;">
                        edit
                    </button>
                    <button @click="deleteComment(comment, post)" class="btn btn-danger" style="width: 9%;margin-left: 661px; margin-top: 8px; margin-bottom: 6px;">
                        delete
                    </button>
                </div>
                <button @click="showMention(comment.id, post.id)" :id="comment.id + '-' + post.id">
                    show the mention in the comment
                </button>

                <div v-if="ViewMention === comment.id + '-' + post.id">
                    <p> {{ mentionUser }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            countPostsHome: 0,
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
            update: null,
            mentionUser: "",
            ViewMention: null,
            idUserMention: "",
            posts: [],
            comments: [],
            post: {
                post_id: "",
                title: "",
                body: ""
            },
            userId: this.$attrs.user_id,
            projects_joined: [],
            allPostsHome: [],
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
            users: [],
            userElement: {
                id: "",
                title: "",
                body: ""
            },
            mentionUserEmpty: "",
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
        //  this.makePagination();
        setTimeout(() => {
            this.fetchPosts();
            this.fetchAllUsers();
        }, 1000)

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
        storeMentionUser(idUserMention, id, post) {
            axios
                .post(
                    `http://127.0.0.1:8000/store-user-in-mention-table/${idUserMention}/comment/${id}/post/${post}/sphere/0`
                )

                .then(res => {
                    if (res.data.status == 404) {
                        this.noDataInSomethingResult();
                    } else if (res.data.status == 401) {
                        this.callErrorMessage(res.data.status)
                    } else {
                        setTimeout(() => {
                            this.callSuccessMessage('Mention');
                        })

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
        updateMentionUser(idUserMention, id, post) {
            axios
                .post(
                    `http://127.0.0.1:8000/update-user-in-mention-table/${idUserMention}/comment/${id}/post/${post}/sphere/0`
                )

                .then(res => {
                    setTimeout(() => {
                        this.
                        this.callSuccessMessage('Mention');
                    })

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
        fetchPosts(page_url) {
            page_url = page_url || '/get-all-posts-home/';
            var userId = this.$attrs.user_id;
            axios(page_url)
                .then(res => {
                    if (res.data.status == 404) {
                        this.noDataInSomethingResult();
                    } else if (res.data.status == 401) {
                        this.callErrorMessage(res.data.status)
                    } else {
                        if (res.data.data !== "") {
                            let vm = this;
                            vm.makePagination(res.data.meta, res.data.links)
                            this.allPostsHome = res.data.data;
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
                next_page_url: links.next,
                prev_page_url: links.prev
            };
            this.pagination = pagination;
        },
        deletePost(id) {
            if (confirm("Are you sure?")) {
                var sphereId = this.$attrs.sphere_id;
                var userId = this.$attrs.user_id;

                axios
                    .delete(`/post/${id}/0/${userId}`, {
                        method: "delete"
                    })
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            alert("post Removed");
                            setTimeout(() => {
                                this.callSuccessMessage('Delete');
                            })

                            this.fetchPosts();
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
        addPostHome() {
            if (this.edit == false) {
                //add
                var userId = this.$attrs.user_id;
                axios
                    .post(`/add-post-home/${userId}`, {
                        body: this.post
                    })
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            if (res.data.message.body != null) {
                                this.post.body = res.data.message.body;
                                this.fetchPosts();

                                setTimeout(() => {
                                    this.callSuccessMessage('Add');
                                }, 1000)

                            } else {
                                this.noDataInYourEntering();
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
                //update
                var sphereId = this.$attrs.sphere_id;
                var userId = this.$attrs.user_id;
                axios.
                put(`/edit-post-home/${userId}`, {
                        body: this.post
                    })
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            if (res.data.message.body != null) {
                                this.post.body = "";
                                this.fetchPosts();
                                setTimeout(() => {
                                    this.callSuccessMessage('Edit');
                                }, 1000)

                                this.edit = false;

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
        PostIndex(X) {
            let I = 0,
                E = this.allPostsHome.length;
            for (; I < E; I++)
                if (this.allPostsHome[I].id == X) return I;
            return null;
        },
        CommentIndex(X, Y) {
            X = this.PostIndex(X);
            if (X !== null) {
                let I = 0,
                    E = this.allPostsHome[X].length;
                for (; I < E; I++)
                    if (this.allPostsHome[X][I].id == Y) return I;
                return null;
            }
            return null;
        },
        addCommentStatus(Post, Comment = null) {
            if (this.edit_comment == false) {
                this.add_comment = true;
                var userElement = document.getElementById("mention");
                var valueUserElement = userElement.value;
                this.userId = this.$attrs.user_id;
                //add
                axios
                    .post(
                        `http://127.0.0.1:8000/post-comment/${Post}/0/${this.userId}`, {
                            body: document.getElementById("comment-" + Post)
                                .value
                        }
                    )
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            if (res.data.data.body != null) {
                                this.fetchPosts();
                                if (valueUserElement !== "") {
                                    axios(
                                        `http://127.0.0.1:8000/show-user/${valueUserElement}`
                                    ).then(result => {
                                        this.idUserMention =
                                            result.data.message.id;
                                        this.storeMentionUser(this.idUserMention, res.data.data.id, Post);

                                        setTimeout(() => {
                                            this.callSuccessMessage('Add');
                                        }, 1000)
                                    }).catch(err => {

                                        if (err.response) {
                                            this.callErrorMessage(err.response.status)
                                        } else {
                                            this.callErrorMessage('Else')
                                        }

                                    });
                                } else {

                                    setTimeout(() => {
                                        this.callSuccessMessage('Add');
                                    }, 1000)
                                }
                            } else {
                                this.noDataInYourEntering();
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
                this.update = null;
                this.userId = this.$attrs.user_id;
                var userElement = document.getElementById("mention");

                var valueUserElement = userElement.value;
                axios
                    .put(
                        `http://127.0.0.1:8000/post-comment/${Comment}/${Post}/0/${this.userId}`, {
                            body: document.getElementById("comment-" + Post)
                                .value
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
                                this.edit_comment = false;
                                this.allPostsHome[this.PostIndex(Post)].comments =
                                    res.data;
                                let comment = this.comments;

                                if (valueUserElement !== "") {
                                    axios(
                                        `http://127.0.0.1:8000/show-user/${valueUserElement}`
                                    ).then(result => {
                                        this.idUserMention =
                                            result.data.message.id;
                                        this.updateMentionUser(this.idUserMention, result.data.data.id, Post);
                                        setTimeout(() => {
                                            this.callSuccessMessage('Edit');
                                        }, 1000)

                                    }).catch(err => {

                                        if (err.response) {
                                            this.callErrorMessage(err.response.status)
                                        } else {
                                            this.callErrorMessage('Else')
                                        }

                                    });
                                }
                                this.fetchPosts();

                            } else {

                                this.noDataInYourEntering();
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

                .then(res => {
                    if (res.data.status == 404) {
                        this.noDataInSomethingResult();
                    } else if (res.data.status == 401) {
                        this.callErrorMessage(res.data.status)
                    } else {
                        if (res.data.message !== null) {
                            axios
                                .get(
                                    `http://127.0.0.1:8000/show-user-email/${res.data.message.user_id}`
                                )
                                .then(res => {
                                    this.mentionUser =
                                        res.data.message.email;

                                })
                                .catch(e); {
                                this.Errors.push("500");
                            }
                        } else {
                            this.mentionUser =
                                "this comment have not mention for any user";
                            // this.noDataInSomethingResult();
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
        editPost(post) {
            this.edit = true;
            this.post.post_id = post.id;
            this.post.title = post.title;
            this.post.body = post.body;
        },
        deleteComment(comment, post) {
            if (confirm("Are you sure?")) {
                var sphereId = this.$attrs.sphere_id;
                var userId = this.$attrs.user_id;
                axios
                    .delete(
                        `/delete-comment/${comment.id}/${post.id}/0/${userId}`
                    )
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            alert("post Removed");
                            setTimeout(() => {
                                this.callSuccessMessage('Delete');
                            }, 1000)

                            this.fetchPosts();
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
        editComment(X, Y) {
            this.update = Y;
            this.edit_comment = true;
            document.getElementById(
                "comment-" + X
            ).value = document.getElementById("old-" + X + "-" + Y).innerText;
            axios
                .get(
                    `http://127.0.0.1:8000/get-user-mention-comment/${Y}/post/${X}/sphere/`
                ).then(res => {
                    if (res.data.message != null) {
                        var userElement = document.getElementById("mention");
                        var valueUserElement = userElement.value;
                        res.data.message.user.email;
                        let r = userElement.setAttribute("value", res.data.message.user.email);

                    }

                })
        },

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
                                    label: `name : ${d.name_task}: desc : ${d.description_task} :didline:${d.end_task}`
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

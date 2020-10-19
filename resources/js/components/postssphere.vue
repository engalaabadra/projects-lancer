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
 hhh   <vc-calendar :attributes="attributes" />

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
        <vc-calendar :attributes="attributes" />
        <h2>Posts Sphere</h2>

        <form @submit.prevent="addPost()" class="sglefeedsec" style="height: 189px;">
            <div class="form-group" style="margin-top: 21px;">
                <textarea class="form-control" placeholder="What do you have to say about the world alaaaaaaaaaaaaaaaaa ?" v-model="post.body"></textarea>
            </div>
            <button type="submit" class="btn btn-info btn-block" style="margin-bottom: 55px;">save</button>
        </form>
        <hr />The Posts
        <hr />
        <div class="card card-body mb-2" v-for="post in posts" v-bind:key="post.id">
            <p v-if="post.user">post : {{ post.user.username }}</p>
            <span style="margin-left: 421px;!important; margin-top: -23px;!important;">{{ post.created_at }}</span>
            <p style="margin-top: 34px;">{{ post.body }}</p>
            <div v-if="post.user_id == userId">

                <button @click="editPost(post)" class="btn btn-success" style=" width: 8%; margin-left: 599px;">edit</button>
                <button @click="deletePost(post.id)" class="btn btn-danger" style="width: 8%;margin-left: 672px; margin-top: -33px;">delete</button>
            </div>
            The Comments:
            <hr />
            <form @submit.prevent="addCommentStatus(post.id,update)">
                <div class="form-group" style="margin-top: 40px;">
                    <textarea type="text" class="form-control" placeholder="Body" :id="'comment-'+post.id" name="body"> </textarea>

                </div>
                <button class="btn btn-info" style=" width: 15%; margin-top: -3px;" placeholder="Write Your Comment ">Add Comment</button>
                <hr />
            </form>
            <div class="card" v-for="comment in post.comments" v-bind:key="comment.id" style="margin-top: 27px;">
                <span style="margin-left: 36px; margin-top: 18px;">{{ comment.user.username }}</span>
                <span style="margin-left: 421px;margin-top: -23px;">{{ comment.created_at }}</span>

                <p style="margin-top: 34px; margin-left: 32px; " :id="'old-'+post.id+'-'+comment.id">{{ comment.body }}</p>
                <div v-if="comment.user_id==userId">
                    <button @click="editComment(post.id,comment.id)" class="btn btn-warning" style="margin-left: 569px;margin-top: -17px; width: 73px; margin-bottom: -41px;">edit</button>
                    <button @click="deleteComment(comment, post)" class="btn btn-danger" style="width: 9%;margin-left: 661px; margin-top: 8px; margin-bottom: 6px;">delete</button>
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
            countPostsSphere: 0,
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
            pagination: {},
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
                id: "",
                title: "",
                body: ""
            },
            userId: "",
            projects_joined: [],
            posts: [],
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
            mentionUserEmpty: ""
        };
    },
    created() {
        // setInterval(() => {
        if (this.countPostsSphere != 0) {

        }
        this.fetchPosts();
        // }, 1000);

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

        fetchPosts(page_url) {
            var userId = this.$attrs.user_id;
            this.sphereId = this.$attrs.sphere_id;
            page_url = page_url || `/get-posts-sphere/${this.sphereId}`;
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
                            this.posts = res.data.data;
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

                axios.delete(`/post/${id}/${sphereId}/${userId}`)
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            alert("post Removed");
                            this.callSuccessMessage('Delete');
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
        addPost() {
            if (this.edit == false) {
                //add
                var userId = this.$attrs.user_id;
                this.sphereId = this.$attrs.sphere_id;
                axios.post(`/add-post-sphere/${this.sphereId}/${userId}`, {
                    body: this.post.body
                    })
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            if (res.data.data.body != null) {
                                this.post.body = res.data.data.body;
                                this.callSuccessMessage('Add');
                                this.fetchPosts();
                            } else {
                                this.noDataInYourEntering();

                            }
                        }
                    })
                    .catch((this.showWrongAlertServer = true), () =>
                        setTimeout(() => {
                            this.showWrongAlertServer = false;
                        }, 3000)
                    );
            } else {
                //update
                var sphereId = this.$attrs.sphere_id;
                var userId = this.$attrs.user_id;
                axios.put(`/edit-post-sphere/${sphereId}/${userId}`, {
                        body: this.post
                    })
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            if (res.data.data.body != null) {
                                this.post.body = "";
                                this.post = false;
                                this.fetchPosts();
                                this.callSuccessMessage('Edit');
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
        PostIndex(X) {
            let I = 0,
                E = this.posts.length;
            for (; I < E; I++)
                if (this.posts[I].id == X) return I;
            return null;
        },
        CommentIndex(X, Y) {
            X = this.PostIndex(X);
            if (X !== null) {
                let I = 0,
                    E = this.posts[X].length;
                for (; I < E; I++)
                    if (this.posts[X][I].id == Y) return I;
                return null;
            }
            return null;
        },
        addCommentStatus(Post, Comment = null) {
            if (this.edit_comment == false) {
                this.add_comment = true;
                this.userId = this.$attrs.user_id;
                this.sphereId = this.$attrs.sphere_id;

                //add
                axios.post(`http://127.0.0.1:8000/post-comment/${Post}/${this.sphereId}/${this.userId}`, {
                        body: document.getElementById('comment-' + Post).value

                    })

                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            if (res.data.data.body != null) {
                                this.posts[this.PostIndex(Post)].comments = res.data;
                                let comment = this.comments;
                                this.callSuccessMessage('Add');

                                this.fetchPosts();

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
                this.update = null;
                this.userId = this.$attrs.user_id;
                axios.put(`http://127.0.0.1:8000/post-comment/${Comment}/${Post}/${this.sphereId}/${this.userId}`, {
                        body: document.getElementById('comment-' + Post).value
                    })

                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            if (res.data.data.body != null) {
                                this.editCommentStatus = true;
                                this.edit_comment = false;
                                this.posts[this.PostIndex(Post)].comments = response.data;
                                let comment = this.comments;
                                this.callSuccessMessage('Edit');

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
                axios.delete(`/delete-comment/${comment.id}/${post.id}/${sphereId}/${userId}`)
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            alert("post Removed");
                            this.callSuccessMessage('Delete');
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
            document.getElementById('comment-' + X).value = document.getElementById('old-' + X + '-' + Y).innerText;
        },
        editC(comment, post) {
            var sphereId = this.$attrs.sphere_id;
            var userId = this.$attrs.user_id;
            axios.put(`/post-comment/${comment.id}/${post.id}/${sphereId}/${userId}`)
                .then(res => {
                    if (res.data.status == 404) {
                        this.noDataInSomethingResult();
                    } else if (res.data.status == 401) {
                        this.callErrorMessage(res.data.status)
                    } else {
                        if (res.data.data.body != null) {
                            this.comment.body = response.data.body;
                            alert("comment updated");
                            this.callSuccessMessage('Edit');

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
    mounted() {
        this.userId = this.$attrs.user_id;
        this.sphereId = this.$attrs.sphere_id;
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

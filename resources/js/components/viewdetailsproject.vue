<template>
<div>
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
    <div>


    </div>
    <form @submit.prevent="fetchaddCommentOnProject()">
        <div class="form-group">
            <textarea type="text" class="form-control" placeholder="Body" v-model="comment.body">
                </textarea>
        </div>
        <button class="btn btn-success" style="    width: 26%;margin-left: 1003px;margin-top: -37px;">
            AddComment on project
        </button>
    </form>

        <div class="card card-body mb-2" v-for="comment in comments_project" v-bind:key="comment.id">
            comment.body {{ comment.body }}
    <div v-if="comment.user_id == userId">
            <button @click="editComment(comment)" class="btn btn-success " style="width: 26%;margin-left: 191px;">
                edit
            </button>
            <button @click="deleteComment(comment.id)" class="btn btn-danger" style="    width: 26%;margin-left: 592px;margin-top: -37px;">
                delete
            </button>
    </div>
        </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            countCommentsProject:0,
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
            edit_comment: false,
            userId: "",
            posts: [],
            spheres: [],
            project_id: "",
            sphere_id: "",
            details_project: [],
            addCommentOnProject: "",
            postsMembersInMyProfile: [],
            comments_project: [],
            spheres_founded: [],
            tasks_accepted: [],
            posts_created: [],
            projects_created: [],
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
            sphere: "",
            post_id: "",
            pagination: {},
            edit: false
        };
    },
    created() {
        // setInterval(() => {
          //  if(this.countCommentsProject!=0){

            //}
            //this.fetchComments();
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
        fetchComments(page_url) {
            this.user_id = this.$attrs.user_id;
            this.project_id = this.$attrs.project_id;
            this.sphere_id = this.$attrs.sphere_id;
            page_url=page_url || `/comments-project/${this.sphere_id}/${this.project_id}`;

            axios(page_url)
                .then(res => {
                    if (res.data.status == 404) {
                        this.noDataInSomethingResult();
                    } else if (res.data.status == 401) {
                        this.callErrorMessage(res.data.status)
                    } else {

                        if (res.data.data !== "") {
                            
                    this.makePagination(res.data.meta,res.data.links)

                            this.comments_project = res.data.data;
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
                next_page_url:links.next,
                prev_page_url: links.prev
            };
            this.pagination = pagination;
        },
        editComment(comment) {
            this.edit_comment = true;
            this.comment.id = comment.id;
            this.comment.body = comment.body;
        },

        fetchaddCommentOnProject() {
            if (this.edit_comment == false) {

                this.sphere_id = this.$attrs.sphere_id;
                this.project_id = this.$attrs.project_id;
                this.user_id = this.$attrs.user_id;
                axios.post(
                        `http://127.0.0.1:8000/add-comment-on-project/${this.sphere_id}/${this.project_id}/${this.user_id}`, {
                            body: this.comment.body
                        }
                    )
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            if (res.data.data == true) {
                                this.comments_project = res.data;
                                this.callSuccessMessage('Add');

                                this.fetchComments();
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
                this.project_id = this.$attrs.project_id;

                axios.put(`http://127.0.0.1:8000/update-comment-project/${this.comment.id}/${this.sphere_id}/${this.project_id}/${this.user_id}`, {
                        body: this.comment.body
                    })
                    .then(res => {
                        if (res.data.status == 404) {
                            this.noDataInSomethingResult();
                        } else if (res.data.status == 401) {
                            this.callErrorMessage(res.data.status)
                        } else {
                            if (res.data.data.body != "") {
                                this.comments_project.push(res.data.data.body);
                                this.edit_comment = false;
                                this.callSuccessMessage('Edit');

                                this.fetchComments();
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
        deleteComment(id) {
            if (confirm("Are you sure?")) {
                this.sphere_id = this.$attrs.sphere_id;
                this.project_id = this.$attrs.project_id;
                this.user_id = this.$attrs.user_id;

                axios.delete(
                        `http://127.0.0.1:8000/delete-comment-project/${id}/${this.sphere_id}/${this.project_id}/${this.user_id}`
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
                    .catch(err => {

                        if (err.response) {
                            this.callErrorMessage(err.response.status)
                        } else {
                            this.callErrorMessage('Else')
                        }

                    });

            }
        },
        makePagination(meta, links) {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev
            };
            this.pagination = pagination;
        }
    },
    mounted() {
            this.fetchComments();

        this.userId = this.$attrs.user_id;
        this.sphere_id = this.$attrs.sphere_id;
            this.project_id = this.$attrs.project_id;

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

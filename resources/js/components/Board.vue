<template>
<div class="container-fluid">
    <!-- Button trigger modal -->

    <modal name="taskInfo" class="col-sm-12 container bg-dark mx-auto" style="padding : 1rem !important">
        <div class="w-100 row">
            <div class="col-sm-8 mr-auto row mt-5 ml-3" style="overflow: scroll !important; max-height: 25rem;">
                <h5 class="col-sm-9 mr-auto" style="font-size: 3rem;font-weight:700">
                    <i class="fas fa-tasks mr-3" style="color:red"></i>
                    {{ taskInfo.name_task }}
                </h5>
                <span>{{view_task_date}}</span>
                <div class="col-sm-12 mx-auto mt-5 row">
                    <form @submit.prevent="addDescrptionTask(taskInfo.id)" class="sglefeedsec" style="height: 189px;">
                        <div v-if="addDescSuccess == false">
                            Description:
                            <input type="button" v-model="task_desc" class="btn-outline-primary form-control" @click="disableDescription()" />
                        </div>

                        <div v-if="addDescSuccess == true" class="row col-sm-12">
                            <textarea class="form-control col-sm-8 mx-auto" id="exampleFormControlTextarea1" rows="2" placeholder="Write Your Description..." style="margin-top: 1rem;" v-model="task.description_task">
                                </textarea>
                            <button class="btn btn-primary col-sm-3" style="margin-top: 1rem;" @click="task_desc = task.description_task">
                                Save
                            </button>
                        </div>
                    </form>
                    <form @submit.prevent="addDidlineTask(taskInfo.id)" class="sglefeedsec" style="height: 189px;">
                        Didline Task :
                        <input type="date" v-model="task_date" class="btn-outline-primary form-control" />
                        <button class="btn btn-primary col-sm-3" style="margin-top: 1rem;">
                            Save
                        </button>
                    </form>

                    {{ taskInfo.category_id }}
                    <form @submit.prevent="
                                addCommentTask(
                                    taskInfo.id,
                                    taskInfo.category_id,
                                    comment.id
                                )
                            " class="sglefeedsec" style="height: 189px;">
                        <textarea class="form-control col-sm-8 mx-auto" id="exampleFormControlTextarea1" rows="2" placeholder="Write a comment..." style="margin-top: 1rem;" v-model="comment.body">
                            </textarea>
                        <button class="btn btn-primary col-sm-3" style="margin-top: 1rem;">
                            Post
                        </button>
                    </form>
                    <div v-for="comment in comments_task" :key="comment.id">
                        <span style="    margin-left: 54px;
    margin-top: 26px;
">{{ comment.user_id }}</span>
                        <span style="margin-left: 421px;
    margin-top: -23px;">{{ comment.created_at }}</span>

                        <p style="    margin-top: 34px;
    margin-left: 48px;">
                            {{ comment.body }}
                        </p>
                        <div v-if="comment.user_id==userId">
                            <button @click="editCommentTask(comment)" class="btn btn-success " style="  width: 9%;
    margin-left: 471px;">
                                edit
                            </button>
                            <button @click="
                                    deleteCommentTask(
                                        comment.id,
                                        taskInfo.category_id,
                                        taskInfo.id
                                    )
                                " class="btn btn-danger" style="     width: 10%;
    margin-left: 538px;
    margin-top: -34px;">
                                delete
                            </button>
                        </div>

                    </div>

                </div>
            </div>
            <div v-if="membersData!==0">

                <div class="col-sm-2 mx-auto" style="margin-top:6rem">

                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="    margin-left: -113px;!important">
                        <i class="fas fa-plus-user mr-3"></i>
                        invit members
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                        <div v-for="member in membersData" :key="member.id" class="dropdown-item ">

                            <button class="btn btn-outline-success" role="button" @click="
                                getUserId(member.id, member.username, taskInfo.id, taskInfo.category_id),
                                putMsgInvit(member.id,taskInfo.id,taskInfo.category_id)

                            ">{{ member.username }}</button>
                            <div class="form-control" :id="member.id"></div>
                        </div>

                    </div>
                    <div v-if="taskInfo.category_id==1">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle mt-5" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-left: -86px;">
                                assign task
                            </button>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div v-for="member in membersData" :key="member.id" class="dropdown-item ">

                                    <button class="btn btn-outline-success" role="button" @click="
                                getUserId(member.id, member.username, taskInfo.id, taskInfo.category_id),
                                putMsgAssign(member.id, taskInfo.id, taskInfo.category_id)
                            ">{{ member.username }}</button>
                                    <div class="row" :id="`assign+${member.id}`" style="visibility: hidden"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                there is no members in sphere, invit members from main page sphere

            </div>
        </div>
    </modal>

    <br class="col-sm-12" />
    <div class="row">
        <div class="col-sm-3 mx-auto">
            <h3>Preparing</h3>
            <draggable class="list-group" :list="cat" group="tasks">
                <div class="list-group-item" v-for="element in cat" @dragenter="getIdTaskCategory(element.id,1)" :key="element.id">
                    id::: {{ element.id }}
                    {{ element.name_task }}

                    <i class="fas fa-edit btn btn-outline-success col-sm-1 mr-auto text-center" @click="editInput(element.id, element.category_id)" style="font-size: .8rem !important;"></i>
                    <i class="fas fa-delete btn btn-outline-danger col-sm-1 mr-auto text-center" @click="deleteInput(element.id)" style="font-size: .8rem !important;"></i>

                    <!-- </button> -->

                    <input type="button" class="col-sm-9 btn btn-primary mx-auto" :value="element.name_task" :id="element.id" style="border: none !important;" @dblclick="
                                $modal.show('taskInfo'),
                                    getTask(element.id),
                                    getComments(
                                        element.id,
                                        element.category_id
                                    ),
                                    getDescriptionTask(element.id),
                                    getDidlineTask(element.id),
                                    (task_desc = element.description_task),
                                    getMsgFromDbForInvitTask(element.id, element.category_id),
                                    getMsgFromDbForAssignTask(element.id, element.category_id)

                            " />
                </div>
                <button v-if="statusVisibleAdd !== 1" class="btn btn-outline-success col-sm-1 mr-auto" @click="addInputStatus(1)">
                    Add
                </button>

                <div v-if="statusVisibleAdd === 1">
                    <form @submit.prevent="addTask(1)">
                        <input type="text" class="col-sm-9 mx-auto" v-model="task.name_task" />
                        <button class="btn btn-warning">Add</button>
                    </form>
                </div>
            </draggable>
        </div>


        <div class="col-sm-3 mx-auto">
            <h3>In Progress</h3>
            <draggable class="list-group" :list="cat2" group="tasks">
                <div class="list-group-item" v-for="element in cat2" :key="element.id" @dragenter="getIdTaskCategory(element.id,2)">
                    {{ element.name_task }}
                    <i class="fas fa-edit btn btn-outline-success col-sm-1 mr-auto text-center" @click="editInput(element.id, element.category_id)" style="font-size: .8rem !important;"></i>
                    <i class="fas fa-delete btn btn-outline-danger col-sm-1 mr-auto text-center" @click="deleteInput(element.id)" style="font-size: .8rem !important;"></i>


                    <input type="button" class="col-sm-9 btn btn-primary mx-auto" :value="element.name_task" :id="element.id" style="border: none !important;" @dblclick="
                                $modal.show('taskInfo'),
                                    getTask(element.id),

                                    getComments(
                                        element.id,
                                        element.category_id
                                    ),
                                    getDidlineTask(element.id)
                            " />
                </div>
                <button v-if="statusVisibleAdd !== 2" class="btn btn-outline-success col-sm-1 mr-auto" @click="addInputStatus(2)">
                    Add
                </button>

                <div v-if="statusVisibleAdd === 2">
                    <form @submit.prevent="addTask(2)">
                        <input type="text" class="col-sm-9 mx-auto" v-model="task.name_task" />
                        <button class="btn btn-warning">Add</button>
                    </form>
                </div>
            </draggable>
        </div>

        <div class="col-sm-3 mx-auto">
            <h3>Awaiting Review</h3>
            <draggable class="list-groupl" :list="cat3" group="tasks">
                <div class="list-group-item" v-for="element in cat3" :key="element.id" @dragenter="getIdTaskCategory(element.id,3)">
                    {{ element.name_task }}
                    <i class="fas fa-edit btn btn-outline-success col-sm-1 mr-auto text-center" @click="editInput(element.id, element.category_id)" style="font-size: .8rem !important;"></i>
                    <i class="fas fa-delete btn btn-outline-danger col-sm-1 mr-auto text-center" @click="deleteInput(element.id)" style="font-size: .8rem !important;"></i>

                    <!-- </button> -->

                    <input type="button" class="col-sm-9 btn btn-primary mx-auto" :value="element.name_task" :id="element.id" style="border: none !important;" @dblclick="
                                $modal.show('taskInfo'),
                                    getTask(element.id),

                                    getComments(
                                        element.id,
                                        element.category_id
                                    ),
                                    getDidlineTask(element.id)
                            " />
                </div>
                <button v-if="statusVisibleAdd !== 3" class="btn btn-outline-success col-sm-1 mr-auto" @click="addInputStatus(3)">
                    Add
                </button>

                <div v-if="statusVisibleAdd === 3">
                    <form @submit.prevent="addTask(3)">
                        <input type="text" class="col-sm-9 mx-auto" v-model="task.name_task" />
                        <button class="btn btn-warning">Add</button>
                    </form>
                </div>
            </draggable>
        </div>
        <div class="col-sm-3 mx-auto row p-0" v-if="leaders_sphere_accepted_request">

            <div class="col-sm-12 mx-auto row p-0" v-for="leader in leaders_sphere_accepted_request" :key="leader.id">
                    <div class="col-sm-12 mx-auto"  v-if="leader.user_id==userId" >
                        <h3>Done</h3>
                        <draggable class="list-group" :list="cat4" group="tasks">
                            <div class="list-group-item" v-for="element in cat4" :key="element.id" @dragenter="getIdTaskCategory(element.id,4)">
                                {{ element.name_task }}
                                <i class="fas fa-edit btn btn-outline-success col-sm-1 mr-auto text-center" @click="editInput(element.id, element.category_id)" style="font-size: .8rem !important;"></i>
                                <i class="fas fa-delete btn btn-outline-danger col-sm-1 mr-auto text-center" @click="deleteInput(element.id)" style="font-size: .8rem !important;"></i>


                                <input type="button" class="col-sm-9 btn btn-primary mx-auto" :value="element.name_task" :id="element.id" style="border: none !important;" @dblclick="
                                $modal.show('taskInfo'),
                                    getTask(element.id),

                                    getComments(
                                        element.id,
                                        element.category_id
                                    ),
                                    getDidlineTask(element.id)
                            " />
                            </div>
                            <button v-if="statusVisibleAdd !== 4" class="btn btn-outline-success col-sm-1 mr-auto" @click="addInputStatus(4)">
                                Add
                            </button>

                            <div v-if="statusVisibleAdd === 4">
                                <form @submit.prevent="addTask(4)">
                                    <input type="text" class="col-sm-9 mx-auto" v-model="task.name_task" />
                                    <button class="btn btn-warning">Add</button>
                                </form>
                            </div>
                        </draggable>
                    </div>
            </div>
        </div>
        <div v-if="leaders_sphere_accepted_invitation">
            <div v-for="leader in leaders_sphere_accepted_invitation" :key="leader.id">
                    <div class="col-sm-3 mx-auto" v-if="leader.user_id==userId">
                        <h3>Done</h3>
                        <draggable class="list-group" :list="cat4" group="tasks">
                            <div class="list-group-item" v-for="element in cat4" :key="element.id" @dragenter="getIdTaskCategory(element.id,4)">
                                {{ element.name_task }}
                                <i class="fas fa-edit btn btn-outline-success col-sm-1 mr-auto text-center" @click="editInput(element.id, element.category_id)" style="font-size: .8rem !important;"></i>
                                <i class="fas fa-delete btn btn-outline-danger col-sm-1 mr-auto text-center" @click="deleteInput(element.id)" style="font-size: .8rem !important;"></i>

                                <!-- </button> -->

                                <input type="button" class="col-sm-9 btn btn-primary mx-auto" :value="element.name_task" :id="element.id" style="border: none !important;" @dblclick="
                                $modal.show('taskInfo'),
                                    getTask(element.id),

                                    getComments(
                                        element.id,
                                        element.category_id
                                    ),
                                    getDidlineTask(element.id)
                            " />
                            </div>
                            <button v-if="statusVisibleAdd !== 4" class="btn btn-outline-success col-sm-1 mr-auto" @click="addInputStatus(4)">
                                Add
                            </button>

                            <div v-if="statusVisibleAdd === 4">
                                <form @submit.prevent="addTask(4)">
                                    <input type="text" class="col-sm-9 mx-auto" v-model="task.name_task" />
                                    <button class="btn btn-warning">Add</button>
                                </form>
                            </div>
                        </draggable>
                    </div>
            </div>
        </div>

    </div>
</div>
</template>

<script>
import draggable from "vuedraggable";
export default {
    name: "two-lists",
    display: "Two Lists",
    order: 1,
    components: {
        draggable
    },
    data() {
        return {
            founder_sphere:[],
            founderSphere: [],
            leaders_sphere_accepted_request: [],
            leaders_sphere_accepted_invitation: [],
            view_task_date: null,
            task_date: null,
            category_id_task: 0,
            messageInvitCanceledIntoTask: "",
            showWrongAlertNotFound: false,
            showWrongAlertUnknown: false,
            showWrongAlertServer: false,
            showWrongAlertForbidden: false,
            showWrongAlertNotAllow: false,
            modalContent: false,
            memberId: null,
            memberName: null,
            task_id: null,
            taskId: null,
            taskCategory: null,
            //
            task: {
                id: "",
                name_task: "",
                description_task: "",
                task_date: null
            },
            tasks: [],
            userId: "",
            sphereId: "",
            project_id: "",
            task_desc: null,
            editedTodo: null,
            membersDataToJoinTask: [],
            messageCanceledAssginTask: "",
            messageInvitIntoTask: "",
            ele: [],
            membersData: [],
            edit: false,
            editingTask: null,
            categoriess: [],
            editedelement: null,
            messageAssginTask: "",
            name: "",
            statusVisibleAdd: false,
            addDescSuccess: false,
            //
            categories: [{
                    id: 1,
                    title: "post1"
                },
                {
                    id: 2,
                    title: "post2"
                },
                {
                    id: 3,
                    title: "post3"
                }
            ],
            // tasks: [
            //   { id: 1, title: "task1", cat_id: 1 },
            //   { id: 2, title: "task2", cat_id: 2 },
            //   { id: 3, title: "task3", cat_id: 3 },
            // ],
            controlOnStart: true,
            cat: [],
            cat2: [],
            cat3: [],
            cat4: [],
            comment: {
                id: "",
                body: ""
            },
            comments_task: [],
            edit_comment_task: false,
            statusVisibleAdd: false,
            taskId: null,
            categoryId: null,
            taskInfo: {},
            taskM: null,
            indexM: null
        };
    },
    methods: {
        putMsgInvit(id, taskId, taskCategory) {
            let userMsg = document.getElementById(id);
            let msg = document.createElement("div");
            let cancel = document.createElement("button");
            userMsg.innerHTML = ""
            msg.setAttribute("class", "col-sm-8 mr-auto");
            msg.style.fontSize = "12px";
            cancel.style.fontWeight = "600";
            cancel.style.fontSize = "10px";
            msg.style.fontWeight = "600";
            cancel.setAttribute("class", "btn btn-outline-danger  col-sm-3 ml-auto");
            cancel.innerHTML = "Cancel";
            cancel.addEventListener("click", (e) => {
                e.preventDefault();
                this.cancelSendInvitationTask(this.taskId, this.taskCategory, this.memberId);
                userMsg.style.visibility = "hidden"
            })
            this.sphereId = this.$attrs.sphere_id;
            this.projectId = this.$attrs.project_id;
            this.userId = this.$attrs.user_id;
            axios
                .post(
                    `http://127.0.0.1:8000/invit-member/${id}/sphere/${this.sphereId}/project/${this.projectId}/task/${taskId}/category/${taskCategory}`
                )
                .then(result => {

                    //   this.messageInvitIntoTask = result.data.data;
                    if (result.data.data == 'sent') {
                        setTimeout(() => {
                            userMsg.style.visibility = "visible"
                            msg.innerText = 'pending your invitation'
                            userMsg.appendChild(msg)
                            userMsg.appendChild(cancel)
                        }, 500)
                    } else if (result.data.data == 'wait') {
                        setTimeout(() => {
                            userMsg.style.visibility = "visible"
                            msg.innerText = `you cannt sent again`
                            userMsg.appendChild(msg)
                            userMsg.appendChild(cancel)
                        }, 500)

                    } else if (result.data.data == 'accepted') {
                        setTimeout(() => {
                            userMsg.style.visibility = "visible"
                            msg.innerText = `Already Exist Here`
                            userMsg.appendChild(msg)
                            userMsg.appendChild(cancel)
                        }, 500)

                    }


                });

        },
        putMsgAssign(memberId, taskId, taskCategory) {
            let userMsgAssign = document.getElementById(`assign+${memberId}`);
            let msgAssign = document.createElement("div");
            let cancelAssign = document.createElement("button");
            userMsgAssign.innerHTML = ""
            msgAssign.setAttribute("class", "col-sm-8 mr-auto");
            msgAssign.style.fontSize = "12px";
            cancelAssign.style.fontWeight = "600";
            cancelAssign.style.fontSize = "10px";
            msgAssign.style.fontWeight = "600";
            cancelAssign.setAttribute("class", "btn btn-outline-danger  col-sm-3 ml-auto");
            cancelAssign.innerHTML = "Cancel";
            cancelAssign.addEventListener("click", (e) => {
                e.preventDefault();
                this.cancelAsigned(taskId);
                this.membersData.forEach(member => {
                    let userMsgAssign = document.getElementById(`assign+${member.id}`);
                    userMsgAssign.style.visibility = "hidden"
                })
                //   userMsgAssign.style.visibility = "hidden"
            })
            this.sphereId = this.$attrs.sphere_id;
            this.projectId = this.$attrs.project_id;
            this.userId = this.$attrs.user_id;

            axios
                .put(
                    `http://127.0.0.1:8000/assign-task-for-member/task/${taskId}/member/${memberId}/sphere/${this.sphereId}/project/${this.projectId}/user/${this.userId}/category/${taskCategory}`
                )
                .then(result => {
                    if (result.data) {
                        if (result.data.data == 'pending_status_task_wait_his_accepting') {
                            userMsgAssign.style.visibility = "visible"
                            msgAssign.innerText = 'wait_his_accepting'
                            userMsgAssign.appendChild(msgAssign)
                            userMsgAssign.appendChild(cancelAssign)
                        } else if (result.data.data == 'pending_status_task_must_be_a_task_for_a_member_just') {
                            userMsgAssign.style.visibility = "visible"
                            msgAssign.innerText = 'this task with another member(pending)'
                            userMsgAssign.appendChild(msgAssign)

                        } else if (result.data.data == 'accepted_status_task_already_for_this_member') {
                            userMsgAssign.style.visibility = "visible"
                            msgAssign.innerText = 'this task already_for_this_member'
                            userMsgAssign.appendChild(msgAssign)
                            userMsgAssign.appendChild(cancelAssign)
                        } else if (result.data.data == 'accepted_status_task_from_another_member_so_must_be_a_task_for_a_member_just') {
                            userMsgAssign.style.visibility = "visible"
                            msgAssign.innerText = 'this task with another member(accepted)'
                            userMsgAssign.appendChild(msgAssign)

                        } else if (result.data.data == 'accepted_status_task_assigned_succefully_wait_his_accepting') {
                            userMsgAssign.style.visibility = "visible"
                            msgAssign.innerText = 'assigned_succefully_wait_his_accepting'
                            userMsgAssign.appendChild(msgAssign)
                            userMsgAssign.appendChild(cancelAssign)

                        }
                    }
                });

        },
        getUserId(id, name, taskId, taskCategory) {
            setTimeout(() => {
                this.memberId = id;
                this.memberName = name;
                this.taskId = taskId;
                this.taskCategory = taskCategory;
            }, 1000)
        },
        getIdTaskCategory(taskId, nextCategoryId) {
            this.category_id_task = nextCategoryId;
            this.task_id = taskId;
            setTimeout(() => {
                if (this.category_id_task !== nextCategoryId) { //to avoid take same id category after time , when i move it in same category
                    axios.put(`/update-category-in-task/${this.task_id}/${nextCategoryId}`).then(res => {
                    })

                }

            }, 300);

        },
        getIdCategory(category_id) {
        },

        getTask(taskId) {
            axios
                .get(`http://127.0.0.1:8000/get-task-data/${taskId}`)
                .then(result => {
                    this.task_desc = result.data.message.description_task
                    this.task_date = result.data.message.end_task
                    this.taskInfo = result.data.message;
                });
        },
        openModal: function () {
            this.modalContent = !this.modalContent;
        },
        getDescriptionTask(task_id) {
            axios
                .get(`http://127.0.0.1:8000/get-description-task/${task_id}`)
                .then(result => {
                    this.task.description_task =
                        result.data.data.description_task;
                });
        },
        getDidlineTask(task_id) {
            axios
                .get(`http://127.0.0.1:8000/get-didline-task/${task_id}`)
                .then(result => {
                    this.view_task_date =
                        result.data.data.end_task;
                });
        },
        disableDescription() {
            this.addDescSuccess = true;
        },
        addInputStatus(id) {
            this.statusVisibleAdd = id;
        },
        addTask(id) {
            this.userId = this.$attrs.user_id;
            this.sphereId = this.$attrs.sphere_id;
            this.projectId = this.$attrs.project_id;
            axios
                .post(
                    `http://127.0.0.1:8000/add-task/${id}/${this.userId}/${this.sphereId}/${this.projectId}`, {
                        name: this.task.name_task
                    }
                )
                .then(response => {
                    setTimeout(() => {
                        if (response.data.message.category_id == 1) {
                            this.cat.push(response.data.message);
                        } else if (response.data.message.category_id == 2) {
                            this.cat2.push(response.data.message);
                        } else if (response.data.message.category_id == 3) {
                            this.cat3.push(response.data.message);
                        } else if (response.data.message.category_id == 4) {
                            this.cat4.push(response.data.message);
                        }
                    }, 1500);
                    this.tasks.push(response.data.message);
                    this.task.name_task = "";
                })
                .catch(err => alert(err));
            input.setAttribute("type", "button");
            setTimeout(() => {
                input.setAttribute(
                    "class",
                    "form-control btn btn-primary col-sm-9"
                );
                input.style.color = "white";
            }, 150);
        },
        getComments(task_id, category_id) {
            this.sphereId = this.$attrs.sphere_id;
            this.projectId = this.$attrs.project_id;
            axios
                .get(
                    `http://127.0.0.1:8000/get-comments/task/${category_id}/${task_id}/sphere/${this.sphereId}/${this.projectId}`
                )
                .then(response => {
                    this.comments_task = response.data.data;
                });
        },
        cancelAsigned(taskId) {
            axios
                .put(
                    `http://127.0.0.1:8000/cancel-asigned/task/${taskId}/sphere/${this.sphereId}/project/${this.projectId}/`
                )
                .then(result => {
                    if (result.data) {
                        this.messageAssginTask = result.data.data;
                    }
                });
        },
        cancelSendInvitationTask(taskId, category_id, userId) {
            setTimeout(() => {

                axios
                    .put(
                        `http://127.0.0.1:8000/cancel-invit/task/${taskId}/category/${category_id}/sphere/${this.sphereId}/project/${this.projectId}/user/${userId}`
                    )
                    .then(result => {
                        if (result.data) {
                            this.messageInvitIntoTask = 'canceled';
                        }
                    });
            }, 2000)
        },
        invitToJoinInTask(memberId, taskId, category_id) {
            this.sphereId = this.$attrs.sphere_id;
            this.projectId = this.$attrs.project_id;
            this.userId = this.$attrs.user_id;
            axios
                .post(
                    `http://127.0.0.1:8000/invit-member/${memberId}/sphere/${this.sphereId}/project/${this.projectId}/task/${taskId}/category/${category_id}`
                )
                .then(result => {

                    this.messageInvitIntoTask = result.data.data;

                });
        },

        getMembers() {
            this.sphereId = this.$attrs.sphere_id;
            this.projectId = this.$attrs.project_id;
            this.userId = this.$attrs.user_id;
            axios
                .get(
                    `http://127.0.0.1:8000/get-members/sphere/${this.sphereId}/project/${this.projectId}/user/${this.userId}`
                )
                .then(result => {
                    this.membersData = result.data.data;
                });
        },
        getMembersToInvitationJoinTask() {
            this.sphereId = this.$attrs.sphere_id;
            this.projectId = this.$attrs.project_id;
            this.userId = this.$attrs.user_id;
            axios
                .get(
                    `http://127.0.0.1:8000/get-members-to-invitation-join-task/sphere/${this.sphereId}/project/${this.projectId}/user/${this.userId}`
                )
                .then(result => {
                    this.membersDataToJoinTask = result.data.data;
                });
        },
        editInput(id) {
            var input = document.getElementById(id);
            input.setAttribute("type", "text");
            input.setAttribute("class", "form-control col-sm-9");
            input.style.color = "black";
            input.addEventListener("keyup", e => {
                if (e.keyCode == 13) {
                    input.setAttribute("type", "button");
                    setTimeout(() => {
                        input.setAttribute(
                            "class",
                            "form-control btn btn-primary col-sm-9"
                        );
                        input.style.color = "white";
                    }, 150);
                }
            });
            input.addEventListener("focusout", () => {
                input.setAttribute("type", "button");
                setTimeout(() => {
                    input.setAttribute(
                        "class",
                        "form-control btn btn-primary col-sm-9"
                    );
                    input.style.color = "white";
                }, 150);
            });
        },

        editInput(id, category_id) {
            this.userId = this.$attrs.user_id;

            var input = document.getElementById(id);
            input.setAttribute("type", "text");
            input.setAttribute("class", "form-control col-sm-9");
            input.style.color = "black";
            input.addEventListener("keyup", e => {
                if (e.keyCode == 13) {
                    this.name = input.value;
                    axios
                        .put(
                            `http://127.0.0.1:8000/update-task/${id}/${category_id}/${this.userId}`, {
                                name: this.name
                            }
                        )
                        .then(response => {
                            this.name = response.message.name_task;
                        })
                        .catch(err => console.log(err));
                    input.setAttribute("type", "button");
                    setTimeout(() => {
                        input.setAttribute(
                            "class",
                            "form-control btn btn-primary col-sm-9"
                        );
                        input.style.color = "white";
                    }, 150);
                }
            });
        },
        deleteInput(id) {
            if (confirm("Are you sure?")) {
                this.userId = this.$attrs.user_id;
                axios(`/get-task-data/${id}`).then(res => {
                    this.taskM = res.data.message;
                    axios
                        .delete(
                            `http://127.0.0.1:8000/delete-task/${id}/${this.userId}`
                        )
                        .then(response => {
                            alert("post Removed");
                            if (this.taskM.category_id == 1) {
                                this.indexM = this.cat.indexOf(this.taskM);
                                this.cat.splice(this.indexM, 1);
                            } else if (this.taskM.category_id == 2) {
                                this.indexM = this.cat2.indexOf(this.taskM);
                                this.cat2.splice(this.indexM, 1);
                            } else if (this.taskM.category_id == 3) {
                                this.indexM = this.cat3.indexOf(this.taskM);
                                this.cat3.splice(this.index, 1);
                            } else if (this.taskM.category_id == 4) {
                                this.indexM = this.cat4.indexOf(this.taskM);
                                this.cat4.splice(this.indexM, 1);
                            }
                        })
                        .catch(err => console.log(err));
                });

            }
        },
        editelement: function (element) {
            this.editedelement = element;
        },

        turn(id, hId) {
            var input = document.getElementById(id),
                toHide = document.getElementById(hId);
            toHide.style.visibility = "hidden";
            setTimeout(() => {
                input.style.visibility = "visible";
            }, 200);
        },
        addNew(id) {
            this.userId = this.$attrs.user_id;

            axios.post(`add-task/${category_id}/${user_id}`).then(response => {
            });
        },
        endEditing(task, category_id) {
            this.userId = this.$attrs.user_id;
            this.editingTask = null;

            axios
                .patch(`task/${task.id}/${category_id}/${this.userId}`)
                .then(response => {
                });
        },

        editTask(task, category_id) {
            this.editingTask = task.id;
        },
        sort() {
            this.categories = this.categories.sort((a, b) => a.order - b.order);
        },
        editCommentTask(comment) {
            this.edit_comment_task = true;
            this.comment.id = comment.id;
            this.comment.body = comment.body;
        },
        addDescrptionTask(id) {
            this.sphereId = this.$attrs.sphere_id;
            this.projectId = this.$attrs.project_id;
            this.userId = this.$attrs.user_id;

            axios
                .post(
                    `http://127.0.0.1:8000/add-description-into-task/${id}/${this.userId}`, {
                        body: this.task.description_task
                    }
                )
                .then(result => {
                    // this.task_desc = result.data.description_task;
                    this.addDescSuccess = false;
                });
        },
        addDidlineTask(id) {
            this.sphereId = this.$attrs.sphere_id;
            this.projectId = this.$attrs.project_id;
            this.userId = this.$attrs.user_id;

            axios
                .post(
                    `http://127.0.0.1:8000/add-didline-into-task/${id}/${this.userId}`, {
                        body: this.task_date
                    }
                )
                .then(result => {
                });
        },
        addCommentTask(id, category_id, comment_id) {
            if (this.edit_comment_task == false) {
                this.sphereId = this.$attrs.sphere_id;
                this.projectId = this.$attrs.project_id;
                this.userId = this.$attrs.user_id;
                axios
                    .post(
                        `http://127.0.0.1:8000/add-comment-on-task/${category_id}/${this.sphereId}/${id}/${this.projectId}/${this.userId}`, {
                            body: this.comment.body
                        }
                    )
                    .then(res => {
                        this.comment.body = res.data.body;
                        this.getComments(id, category_id);
                    });
            } else {
                this.sphereId = this.$attrs.sphere_id;
                this.userId = this.$attrs.user_id;

                this.projectId = this.$attrs.project_id;
                axios
                    .put(
                        `http://127.0.0.1:8000/update-comment-task/${category_id}/${comment_id}/${this.sphereId}/${id}/${this.projectId}/${this.userId}`, {
                            body: this.comment.body
                        }
                    )
                    .then(response => {
                        this.edit_comment_task = false;
                        this.getComments(id, category_id);
                    })
                    .catch(err => console.log(err));
            }
        },
        deleteCommentTask(comment_id, category_id, task_id) {
            if (confirm("Are you sure?")) {
                this.sphereId = this.$attrs.sphere_id;
                this.projectId = this.$attrs.project_id;
                this.userId = this.$attrs.user_id;
                axios
                    .delete(
                        `/delete-comment-task/${category_id}/${comment_id}/${this.sphereId}/${task_id}/${this.projectId}/${this.userId}`
                    )
                    .then(response => {
                        alert("post Removed");
                        this.getComments(task_id, category_id);
                    })
                    .catch(err => console.log(err));
            }
        },

        fetchAll() {
            this.cat = [];
            this.cat2 = [];
            this.cat3 = [];
            this.cat4 = [];
            this.sphereId = this.$attrs.sphere_id;
            this.projectId = this.$attrs.project_id;
            let token = localStorage.getItem("jwt");
            axios.defaults.headers.common["Content-Type"] = "application/json";
            axios.defaults.headers.common["Authorization"] = "Bearer " + token;

            axios
                .get(
                    `http://127.0.0.1:8000/category/project/${this.projectId}/sphere/${this.sphereId}`
                )
                .then(response => {
                    this.categoriess.push(response.data);
                    response.data.forEach(el => {
                        axios
                            .get(
                                `http://127.0.0.1:8000/category/${el.id}/tasks/project/${this.projectId}/sphere/${this.sphereId}`
                            )
                            .then(result => {
                                //fetch for check if this member is joined in it

                                this.tasks.push(result.data);
                                let arr = [];
                                this.tasks.forEach(task => {
                                    task.forEach(item => {
                                        arr.push(item);
                                    });
                                });
                                for (var i = 0; i < arr.length; i++) {

                                    if (arr[i].category_id == 1) {
                                        if (!this.cat.includes(arr[i])) {
                                            this.cat.push(arr[i]);
                                        }
                                    } else if (arr[i].category_id == 2) {
                                        if (!this.cat2.includes(arr[i])) {
                                            this.cat2.push(arr[i]);
                                        }
                                    } else if (arr[i].category_id == 3) {
                                        if (!this.cat3.includes(arr[i])) {
                                            this.cat3.push(arr[i]);
                                        }
                                    } else if (arr[i].category_id == 4) {
                                        if (!this.cat4.includes(arr[i])) {
                                            this.cat4.push(arr[i]);
                                        }
                                    }
                                }
                            });
                    });
                });
        },

        getMsgFromDbForInvitTask(taskId, categoryTask) {
            setTimeout(() => {
                this.membersData.forEach(member => {
                    let userMsg = document.getElementById(member.id);
                    let msg = document.createElement("div");
                    let cancel = document.createElement("button");
                    msg.setAttribute("class", "col-sm-8 mr-auto");
                    msg.style.fontSize = "12px";
                    cancel.style.fontWeight = "600";
                    cancel.style.fontSize = "10px";
                    msg.style.fontWeight = "600";
                    cancel.setAttribute("class", "btn btn-outline-danger  col-sm-3 ml-auto");
                    cancel.innerHTML = "Cancel";
                    cancel.addEventListener("click", (e) => {
                        e.preventDefault();
                        this.cancelSendInvitationTask(taskId, categoryTask, member.id);
                        userMsg.style.visibility = "hidden"
                    })
                    setTimeout(() => {
                        axios
                            .post(
                                `http://127.0.0.1:8000/get-data-task-member/${member.id}/sphere/${this.sphereId}/project/${this.projectId}/task/${taskId}/category/${categoryTask}`
                            )
                            .then(result => {
                                setTimeout(() => {
                                    this.messageInvitIntoTask = result.data.data;
                                }, 1000);

                                if (result.data.data == 'wait') {
                                    setTimeout(() => {
                                        userMsg.style.visibility = "visible"
                                        msg.innerHTML = `you cannt sent again`;
                                        userMsg.appendChild(msg)
                                        userMsg.appendChild(cancel)

                                    }, 1000);

                                } else if (result.data.data == 'sent') {
                                    setTimeout(() => {
                                        userMsg.style.visibility = "visible"
                                        msg.innerHTML = 'pending your invitation';
                                        userMsg.appendChild(msg)
                                        userMsg.appendChild(cancel)

                                    }, 1000);

                                } else if (result.data.data == 'accepted') {
                                    setTimeout(() => {
                                        userMsg.style.visibility = "visible"
                                        msg.innerHTML = `Already Exist Here`;
                                        userMsg.appendChild(msg)
                                        userMsg.appendChild(cancel)

                                    }, 1000);

                                }

                            });
                    }, 500)
                })
            }, 1000)

        },
        getMsgFromDbForAssignTask(taskId, categoryTask) {
            setTimeout(() => {
                this.membersData.forEach(member => {

                    let userMsgAssign = document.getElementById(`assign+${member.id}`);
                    let msgAssign = document.createElement("div");
                    let cancelAssign = document.createElement("button");
                    msgAssign.setAttribute("class", "col-sm-8 mr-auto");
                    msgAssign.style.fontSize = "12px";
                    cancelAssign.style.fontWeight = "600";
                    cancelAssign.style.fontSize = "10px";
                    msgAssign.style.fontWeight = "600";
                    cancelAssign.setAttribute("class", "btn btn-outline-danger  col-sm-3 ml-auto");
                    cancelAssign.innerHTML = "Cancel";
                    cancelAssign.addEventListener("click", (e) => {
                        e.preventDefault();
                        this.cancelAsigned(taskId);
                        this.membersData.forEach(member => {
                            let userMsgAssign = document.getElementById(`assign+${member.id}`);
                            userMsgAssign.style.visibility = "hidden"
                        })
                    })
                    this.sphereId = this.$attrs.sphere_id;
                    this.projectId = this.$attrs.project_id;
                    this.userId = this.$attrs.user_id;
                    setTimeout(() => {
                        axios
                            .get(
                                `http://127.0.0.1:8000/get-assign-task-for-member/task/${taskId}/member/${member.id}/sphere/${this.sphereId}/project/${this.projectId}/user/${this.userId}/category/${categoryTask}`
                            )
                            .then(result => {
                                if (result.data) {
                                    if (result.data.data == 'pending_status_task_wait_his_accepting') {
                                        userMsgAssign.style.visibility = "visible"
                                        msgAssign.innerText = 'wait_his_accepting'
                                        userMsgAssign.appendChild(msgAssign)
                                        userMsgAssign.appendChild(cancelAssign)
                                    } else if (result.data.data == 'pending_status_task_must_be_a_task_for_a_member_just') {
                                        userMsgAssign.style.visibility = "visible"
                                        msgAssign.innerText = 'this task with another member(pending)'
                                        userMsgAssign.appendChild(msgAssign)
                                    } else if (result.data.data == 'accepted_status_task_already_for_this_member') {
                                        userMsgAssign.style.visibility = "visible"
                                        msgAssign.innerText = 'this task already_for_this_member'
                                        userMsgAssign.appendChild(msgAssign)
                                        userMsgAssign.appendChild(cancelAssign)
                                    } else if (result.data.data == 'accepted_status_task_from_another_member_so_must_be_a_task_for_a_member_just') {
                                        userMsgAssign.style.visibility = "visible"
                                        msgAssign.innerText = 'this task with another member(accepted)'
                                        userMsgAssign.appendChild(msgAssign)
                                    }
                                }
                            });
                    }, 1500)

                })
            }, 5500)

        }
    },
    mounted() {
        this.founder_sphere = this.$attrs.founder_sphere;
        var leaderAcceptedInvitation=JSON.parse(this.$attrs.leaders_sphere_accepted_invitation);
        var i;
        for(i=0;i<leaderAcceptedInvitation.length;i++){
            this.leaders_sphere_accepted_invitation.push(leaderAcceptedInvitation[i])
        }

        var leaderAcceptedRequested=JSON.parse(this.$attrs.leaders_sphere_accepted_request);
        var j;
        for(j=0;j<leaderAcceptedRequested.length;j++){
            this.leaders_sphere_accepted_request.push(leaderAcceptedRequested[j])
        }
        this.founderSphere = this.$attrs.founderSphere;
        this.fetchAll();
        this.getMembersToInvitationJoinTask();
        this.getMembers();
    }
};
</script>

<style>
.vm--modal {
    top: 10px !important;
    left: 100px !important;
    right: 75px !important;
    width: 1000px !important;
    height: 1500px !important;
    overflow-y: scroll;
}

.modal__layer {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.2);
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.6s;
}
</style>

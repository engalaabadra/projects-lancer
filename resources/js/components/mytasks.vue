<template>
<div>
    <div class="bodysec survey">
        <div class="survey-details-back">
            <div class="main_back">
                <div class="left_task">
                    <div class="task-left-head">
                        <div class="tmmytasksleft-heading">
                            <h2>My Spheres</h2>
                        </div>
                        <div class="tm-my-spheres">
                            <ul>
                                <div v-if="mySpheresFounded">
                                    <div v-for="mySphereFounded in mySpheresFounded" :key="mySphereFounded.id">
                                        <li><span>f</span>
                                            <a :href="`/sphere/${mySphereFounded.id}/posts`">{{mySphereFounded.name}}</a>
                                        </li>
                                    </div>
                                </div>
                                <div v-else>
                                    <li>
                                        Not Exist
                                    </li>
                                </div>

                                <div v-if="mySpheresJoined">
                                    <div v-for="mySphereJoined in mySpheresJoined" :key="mySphereJoined.id">
                                        <li><span>j</span>
                                            <a :href="`/sphere/${mySphereJoined.id}/posts`">{{mySphereJoined.name}}</a>
                                        </li>
                                    </div>
                                </div>
                                <div v-else>
                                    <li>
                                        Not Exist
                                    </li>
                                </div>

                            </ul>
                            <a href="/user/my-spheres" class="tm-my-spheres-btn">More...</a>

                        </div>
                    </div>

                    <vc-calendar :attributes="attributes" />

                </div>

                <div class="middle_section column-section">
                    <div v-if="ErrorBool == true">
                        <div v-if="Errors">
                            <div class="alert alert-danger" v-for="Error in Errors" :key="Error">
                                {{ getError(Error) }}
                            </div>
                        </div>
                    </div>
                    <div class="tmmytasksmain-middle-panel">
                        <ul>
                            <li>
                                <h2>My Assigned Tasks</h2>
                                <table>
                                    <tbody>
                                        <div v-if="my_tasks_assigned_for_me">
                                            <tr>
                                                <th>Title</th>
                                                <th>From</th>
                                                <th>To</th>
                                            </tr>

                                            <tr>
                                                <div v-for="task in my_tasks_assigned_for_me" :key="task.id">
                                                    <td>{{task.name_task}}</td>
                                                    <td>{{task.start_task}}</td>
                                                    <td>{{task.end_task}}</td>
                                                </div>
                                            </tr>

                                        </div>
                                        <div v-else>
                                            <div class="alert alert-info">
                                                there is no tasks assigned for you , until now
                                            </div>
                                        </div>

                                    </tbody>
                                </table>
                            </li>
                            <li>
                                <h2>My created Tasks</h2>
                                <table>
                                    <tbody>
                                        <div v-if="my_tasks_created_by_me">
                                            <tr>
                                                <th>Title</th>
                                                <th>From</th>
                                                <th>To</th>
                                            </tr>

                                            <tr v-for="task in my_tasks_created_by_me" :key="task.id">
                                                <td>{{task.name_task}}</td>
                                                <td>{{task.start_task}}</td>
                                                <td>{{task.end_task}}</td>
                                            </tr>

                                        </div>
                                        <div v-else>
                                            <div class="alert alert-info">
                                                there is no tasks assigned for you , until now
                                            </div>
                                        </div>

                                    </tbody>
                                </table>
                            </li>

                            <li>
                                <h2>Open Tasks in my Spheres</h2>
                                <table>
                                    <tbody>
                                        <div v-if="my_tasks_open">
                                            <tr>
                                                <th>Title</th>
                                                <th>From</th>
                                                <th>To</th>
                                            </tr>
                                            <div v-for="task in my_tasks_open" :key="task.id">
                                                <tr>
                                                    <td>{{task.name_task}}</td>
                                                    <td>{{task.start_task}}</td>
                                                    <td>{{task.end_task}}</td>
                                                </tr>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <div class="alert alert-info">
                                                there is no tasks opening , until now
                                            </div>
                                        </div>

                                    </tbody>
                                </table>
                            </li>

                            <li>
                                <h2>Completed Tasks</h2>
                                <table>
                                    <tbody>
                                        <div v-if="my_tasks_close">
                                            <tr>
                                                <th>Title</th>
                                                <th>From</th>
                                                <th>To</th>
                                            </tr>
                                            <div v-for="task in my_tasks_close" :key="task.id">
                                                <tr>
                                                    <td>{{task.name_task}}</td>
                                                    <td>{{task.start_task}}</td>
                                                    <td>{{task.end_task}}</td>
                                                </tr>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <div class="alert alert-info">
                                                there is no tasks opening , until now
                                            </div>
                                        </div>

                                    </tbody>
                                </table>
                            </li>

                        </ul>
                    </div>
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
            my_tasks_close: [],
            my_tasks_open: [],
            my_tasks_assigned_for_me: [],
            my_tasks_created_by_me: [],
            mySpheresFounded: [],
            mySpheresJoined: []
        };
    },
    methods: {
                getError(id) {
            if (this.ErrorMessage.hasOwnProperty(id))
                return this.ErrorMessage[id];

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
    },
    mounted() {

        axios
            .get(
                'http://127.0.0.1:8000/user/my-spheres-founded'
            )

            .then(res => {
    
                this.mySpheresFounded = res.data.data;
            }).catch(err => this.callErrorMessage(err.response.status))

        axios
            .get(
                'http://127.0.0.1:8000/user/my-tasks-created-by-me'
            )

            .then(res => {
    
                this.my_tasks_created_by_me = res.data.data;
            }).catch(err => this.callErrorMessage(err.response.status))

        axios
            .get(
                'http://127.0.0.1:8000/user/my-tasks-assign-for-me'
            )

            .then(res => {
    
                this.my_tasks_assigned_for_me = res.data.data;
            }).catch(err => this.callErrorMessage(err.response.status))

        axios
            .get(
                'http://127.0.0.1:8000/user/my-tasks-closed'
            ).catch(err => this.callErrorMessage(err.response.status))

            .then(res => {
    
                this.my_tasks_close = res.data.data;
            }).catch(err => this.callErrorMessage(err.response.status))

        axios
            .get(
                'http://127.0.0.1:8000/user/my-spheres-joined'
            )

            .then(res => {
    
                this.mySpheresJoined = res.data.data;
            }).catch(err => this.callErrorMessage(err.response.status))

        axios
            .get(
                'http://127.0.0.1:8000/user/my-tasks-open'
            )

            .then(res => {
    
                this.my_tasks_open = res.data.data;
            }).catch(err => this.callErrorMessage(err.response.status))
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

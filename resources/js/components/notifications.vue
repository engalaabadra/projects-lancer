<template>
<div>
    <ul>
        <a class="cartbox_active">
            <span class="glyphicon glyphicon-globe" v-if="unreadCount > 0 ">{{unreadCount}}</span> notifications <span class="badge"> </span>
        </a>
        <div class="minicart-content-wrapper" v-if="unreadCount>0">
            <div class="item01 d-flex mt--20" v-for="item in unread" :key="item.id">
                
                    <div v-if="item.data.type_notification=='mentionUser'">
                        <div class="content">
                            you have new mention :<a :href="`http://127.0.0.1:8000/user/details-post-dashboard/${item.data.post_id}/0`" @click="readNotifications(item)"> {{item.data.comment}} </a>on your post dasboard
                        </div>

                    </div>
                    <div v-else-if="item.data.type_notification=='commentUser'">
                        <div class="content">
                            you have new comment :<a :href="`http://127.0.0.1:8000/user/details-post-dashboard/${item.data.post_id}/0`" @click="readNotifications(item)"> {{item.data.comment}} </a>on your post dasboard
                        </div>
                    </div>
              
            </div>
        </div>
    </ul>
</div>
</template>

<script>
export default {
    data: function () {
        return {
            read: {},
            unread: {},
            all: {},
            unreadCount: 0,
            typeNotification: ''
        }
    },
    created() {
       setInterval(() => {
        this.getNotifications();
        },500)
        let userId = $('meta[name="userId"]').attr('content');

    },
    methods: {
        getNotifications() {
            axios.get('/user/notifications/get').then(res => {
                    this.all = res.data.all;
                    this.read = res.data.read;
                    this.unread = res.data.unread;
                    this.unreadCount = res.data.unread.length;
                })
                .catch(err => Exception.handle(err));
        },
        //when click on a comment will call this , which is through this will make this notification in readNotification because we put it in markAsRead
        //which is after that when get all notifications will be this notification in read
        readNotifications(notification) {
            axios.post(`http://127.0.0.1:8000/user/notifications/read/${notification.id}`).then(res => { })
                .catch(err => Exception.handle(err))
        }
    }
}
</script>

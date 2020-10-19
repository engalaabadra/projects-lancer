require('./bootstrap');
window.Vue = require('vue');
import notifications from './components/notifications.vue';
import postshome from './components/postshome.vue';
import myspheres from './components/myspheres.vue';
import mytasks from './components/mytasks.vue';
import myprojects from './components/myprojects.vue';
 import postssphere from './components/postssphere.vue';
 import viewdetailsproject from './components/viewdetailsproject.vue';
import viewdetailspostdashboard from './components/viewdetailspostdashboard.vue';
import viewdetailspost from './components/viewdetailspost.vue';
import voicecall from './components/voicecall.vue';
import videochat from './components/videochat.vue';
import message from './components/message.vue';
 import Board from './components/Board.vue';
 import Conversation from './components/Conversation.vue';
import chatroom from './components/chatroom.vue';
import chattwomembers from './components/chattwomembers.vue';

import VModal from 'vue-js-modal';
import "../../node_modules/@fortawesome/fontawesome-free/css/all.css";
import Bootstrap from "bootstrap";
import "../../node_modules/bootstrap/dist/js/bootstrap.js";
import vuetify from "./plugins/vuetify";
import VueChatScroll from 'vue-chat-scroll';
import Toaster from 'v-toaster';
import VCalendar from 'v-calendar';

import Vue from 'vue';
//for auto scroll
Vue.use(VueChatScroll);
//for notifications
Vue.use(Toaster,{timeout:5000});

Vue.use(VModal);

// Use v-calendar & v-date-picker components
Vue.use(VCalendar, {
  componentPrefix: 'vc'
});
window.onload = function(){
   const app = new Vue({
   el: '#app',
   components : { 
      vuetify,
      myprojects,
      myspheres,
      mytasks,
      chattwomembers,
      chatroom,
      message,
      notifications,
      voicecall,
      videochat,
      postshome,
      postssphere,
      viewdetailsproject,
      viewdetailspostdashboard,
      viewdetailspost,
      Board,
      Conversation,
      Bootstrap
   }
});  
}


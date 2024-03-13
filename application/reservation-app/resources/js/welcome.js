import './bootstrap';
import { createApp } from 'vue';
import Welcome from "./components/Welcome.vue";
import router from './router';
import VueCookies from 'vue-cookies';
import Vuex from 'vuex';
import store from './store';
import axios from 'axios';
const app=createApp({
    components:{
        Welcome,
    },
});
app.use(router);
app.use(VueCookies);
app.use(Vuex);
app.use(store);
//app.use(axios);
app.mount("#welcome");

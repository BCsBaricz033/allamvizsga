import './bootstrap';
import { createApp } from 'vue';
import Welcome from "./components/Welcome.vue";
import router from './router';

const app=createApp({
    components:{
        Welcome,
    },
});
app.use(router);
app.mount("#welcome");
import { createRouter,createWebHistory } from "vue-router";
import Login from "../components/Login.vue";
import Register from "../components/Register.vue";
import Welcome from '../components/Welcome.vue';
import AdminMenu from '../components/adminPages/AdminMenu.vue';
import NewDates from '../components/adminPages/NewDates.vue';
import Riports from '../components/adminPages/Riports.vue';
import Users from '../components/adminPages/Users.vue';
import DoctorMenu from '../components/doctorPages/DoctorMenu.vue';
import Reservations from '../components/doctorPages/Reservations.vue';
import Today from '../components/doctorPages/Today.vue';
import UserMenu from '../components/userPages/UserMenu.vue';
import DateReservation from '../components/userPages/DateReservation.vue';
import OwnDates from '../components/userPages/OwnDates.vue';
import AssistantMenu from '../components/assistantPages/AssistantMenu.vue';
import axios from 'axios';
import store from '../store';







const routes= [
    // welcome
    
    {
        path:'/',
        name:'Welcome',
        component:Welcome

    },
    

    //login

    {
        path:'/login',
        name:'Login',
        component:Login
        

    },

    //register
    
    {
        path:'/register',
        name:'Register',
        component:Register
        
    },

    // admin pages
    


    
    {
        path: '/admin',
        name:'Admin',
        component: AdminMenu,
        meta: { requiresAuth: true, requiresAdmin: true },
        children: [
          {
            path: '', 
            redirect: { name: 'Users',component:Users },
            
          },
          {
            path: 'users',
            name: 'Users',
            component: Users,
            
          },
          {
            path: 'new_dates',
            name: 'NewDates',
            component: NewDates,
            
          },
          {
            path: 'riports',
            name: 'Riports',
            component: Riports,
            
          }
        ]
      },
      
    // doctor pages
    {
      path: '/doctor',
      name:'Doctor',
      component: DoctorMenu,
      meta: {
        requiresAuth: true,
        requiresDoctor: true
    },
      children: [
        {
          path: '', 
          redirect: { name: 'Today',component:Today } 
        },
        {
          path: 'today',
          name: 'Today',
          component: Today
        },
        {
          path: 'reservations',
          name: 'DoctorReservations',
          component: Reservations
        },
      ]
    },

    // user pages

    {
      path: '/user',
      name:'User',
      component: UserMenu,
      meta: {
        requiresAuth: true,
        requiresUser: true
    },
      children: [
        {
          path: '', 
          redirect: { name: 'UserDateReservation',component:DateReservation } 
        },
        {
          path: 'date_reservation',
          name: 'UserDateReservation',
          component: DateReservation
        },
        {
          path: 'own_dates',
          name: 'OwnDates',
          component: OwnDates
        },
      ]
    },


    // assistant pages
    {
      path:'/assistant',
      name:'Assistant',
      component:AssistantMenu,
      meta: {
        requiresAuth: true,
        requiresAssistant: true
      }
      
    },


    

    

];
const router=createRouter({
    history:createWebHistory(),
    routes
});
/*
router.beforeEach(async (to, from, next) => {
  if (to.meta.requiresAuth) {
    if (store.state.auth.authenticated) {
      next(); 
    } else {
      next({ name: "Login" }); 
    }
  } else {
    next(); 
  }
});*/






export default router;

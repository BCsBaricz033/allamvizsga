import { createRouter,createWebHistory } from "vue-router";
import Login from "../components/Login.vue";
import Register from "../components/Register.vue";
import Welcome from '../components/Welcome.vue';
import AdminMenu from '../components/adminPages/AdminMenu.vue';
import NewDates from '../components/adminPages/NewDates.vue';
import Riports from '../components/adminPages/Riports.vue';
import Users from '../components/adminPages/Users.vue';
//import DoctorMenu from '../components/doctorPages/DoctorMenu.vue';
import Reservations from '../components/doctorPages/Reservations.vue';
import Today from '../components/doctorPages/Today.vue';
//import UserMenu from '../components/userPages/UserMenu.vue';
import DateReservation from '../components/userPages/DateReservation.vue';
import OwnDates from '../components/userPages/OwnDates.vue';






const routes= [
    // welcome
    
    {
        path:'/',
        name:'Welcome',
        component:Welcome

    },
    
   /*
    {
        path:'/welcome',
        name:'Welcome',
        component:Welcome

    },*/

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
        children: [
          {
            path: '', // Ez az üres útvonal jelzi az alapértelmezett oldalt
            redirect: { name: 'Users',component:Users } // Átirányítás a 'Users' útvonalra
          },
          {
            path: 'users',
            name: 'Users',
            component: Users
          },
          {
            path: 'new_dates',
            name: 'NewDates',
            component: NewDates
          },
          {
            path: 'riports',
            name: 'Riports',
            component: Riports
          }
        ]
      },

    // doctor pages

    {
        path:'/doctor/today',
        name:'Today',
        component:Today

    },
    {
        path:'/doctor/reservations',
        name:'DoctorReservations',
        component:Reservations

    },

    // user pages

    {
        path:'/user/date_reservation',
        name:'UserDateReservation',
        component:DateReservation

    },
    {
        path:'/user/own_dates',
        name:'OwnDates',
        component:OwnDates

    },


    

    

];
const router=createRouter({
    history:createWebHistory(),
    routes
});
export default router;
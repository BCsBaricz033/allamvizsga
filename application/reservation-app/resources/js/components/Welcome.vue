<template>
    <h1>This is the  welcome page</h1>
    <!--
        
    <router-link to="/login">Bejelentkezés</router-link>
    <router-link to="/register">Regisztráció</router-link>
    <router-view></router-view>
    -->
    <!--
        <a href="/login">Bejelentkezés</a>
    <a href="/register">Regisztráció</a>
    -->
    <router-link :to="{ name: 'Login'}">Bejelentkezés</router-link>
    <router-link :to="{ name: 'Register'}">Regisztráció</router-link>
    
    
    
</template>
<template>
  <div id="content">

    <div class="col-sm-4">
      <h1 align="center" class="loginTitle"> Login</h1>
      <p v-if="errors.length" class="error">
    <ul>
      <li v-for="error in errors">{{ error }}</li>
    </ul>
  </p>
      <form @submit.prevent="LoginData" method="post">


        <div class="form-group" align="left">
          <label>Email</label>
          <input type="email" v-model="user.email" class="form-control" placeholder="Email" required>
        </div>


        <div class="form-group" align="left">
          <label>Password</label>
          <input type="password" v-model="user.password" class="form-control" placeholder="Password" required>
        </div>

        <button type="submit">Login</button>
      </form>
      <router-link :to="{ name: 'Register'}">Regisztráció</router-link>
    </div>
  </div>
</template>
   
<script>
import { mapActions } from 'vuex';
import VueCookies from 'vue-cookies';
export default {
  name: 'Registation',
  data() {
    return {
      result: {},
      user: {
        email: '',
        password: ''
      },
      errors:[],
    }
  },
  created() {
    
  },
  mounted() {
    console.log("mounted() called.......");  
    
  },
  methods: {
    ...mapActions({
            signIn:'auth/login'
        }),
    async LoginData() {
      this.errors=[];
      await axios.get('/sanctum/csrf-cookie');
      //console.log(this.user);
      await axios.post("http://127.0.0.1:8000/api/login", this.user,)
          .then(
          ({ data }) => {
          this.signIn();
            alert('jo');
            //console.log(data);
            try {
              if (data.status === true) {
                if(data.role==0){
                  this.$router.push({ path: '/user/' });
                }
                else if(data.role==1){
                  this.$router.push({ path: '/doctor/' })
                }
                else if(data.role==2){
                  this.$router.push({ path: '/assistant/' })
                }

                else if(data.role==3){
                  this.$router.push({ path: '/admin/' })
                }
                
                

                //this.$router.push({ path: '/admin/' })
                //window.location.href = 'admin';
                //this.$router.go({ name: 'Users' })
              } else {
                //alert("Login failed")
                this.errors.push("Email or password invalid");
              }

            } catch (err) {
              //console.log(err);
              //alert("Error, please try again");
              this.errors.push("Error, please try again");
            }
          }
        )
    }
  },
  
}
</script>
<style>
  #content
  {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  input {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  }
  button
  {
    background-color: #04AA6D; /* Green */
    border: 1px solid #ccc;
    color: black;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
  }
  .error{
    color:red
  }


  
</style>
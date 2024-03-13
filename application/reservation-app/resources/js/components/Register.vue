<template>

    <div id="content" align="left">
            <div class="card-header">Register Form</div>
            <div class="card-body"> 
            
                <form  @submit.prevent="saveData">
                
                <label>Name</label>
                <input type="text" v-model="user.name" name="name" id="name" class ="form-control" required/> 
    
          
                <label>Email</label>
                <input type="email" v-model="user.email" name="email" id="email" class ="form-control" required/>
    
                <label>Password</label>
                <input type="password" v-model="user.password" name="password" id="password" class ="form-control" required/> 
                
                <label>Password again</label>
                <input type="password" v-model="user.secondpassword" name="secondpassword" id="secondpassword" class ="form-control" required/>
    
                <label>Phone</label>
                <input type="text" v-model="user.phone" name="phone" id="phone" class ="form-control" required/> 

                <label>Adress</label>
                <input type="text" v-model="user.adress" name="adress" id="adress" class ="form-control"/> 

                <label>Birthdate</label>
                <input type="date" v-model="user.birthdate" name="birthdate" id="birthdate" class ="form-control" required/> 

                <label>CNP</label>
                <input type="text" v-model="user.cnp" name="cnp" id="cnp" class ="form-control"/> 

                <input type="submit" value="Save"> 
    
    
                </form>
                <router-link :to="{ name: 'Login'}">Bejelentkezés</router-link>
            </div>
        </div>
</template>
       
<script>
    

    export default {
    name: 'Register',
    data () {
        return {
        result: {},
        user:{
                    name: '',
                    email: '',
                    password: '',
                    secondpassword:'',
                    phone:'',
                    adress:'',
                    birthdate:'',
                    cnp:'',

        }
        }
    },
    created() { 
    },
    mounted() {
            console.log("mounted() called.......");
        },
    methods: {
            saveData()
            {
            axios.post("http://127.0.0.1:8000/api/register", this.user)
            .then(
                ({data})=>{
                console.log(data);
                try 
                    {
                    alert("saved");
                    
                    } 
                catch(err) 
                    {
                    alert("failed");
                    }    
                }
            )
            }
        }
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
  
  
</style>

<template>
  <div>
      <h2>Users</h2>
      <button @click="showAddModal()">Add user</button>
      <ul>
          <li v-for="user in users" :key="user.id">
              {{ user.name }} - {{ user.email }}
              <button @click="editUser(user)">Edit</button>
              <button @click="showDeleteModal=true;deleteId=user.id">Delete</button>
          </li>
      </ul>
      <div class="modal" v-if="showModal">
            <div class="modal-content">
                <span class="close" @click="showModal = false">&times;</span>
                <h2 v-if="!editing">Add User</h2>
            <h2 v-else>Edit User</h2>

            <form  @submit.prevent="submitForm">
                <label>Name:</label>
                <input type="text" v-model="formData.name" required>

                <label>Email:</label>
                <input type="email" v-model="formData.email" required>

                <label v-if="editing==false">Password:</label>
                <input v-if="editing==false" type="password" v-model="formData.password" required>

                <label>Phone:</label>
                    <input type="text" v-model="formData.phone" required>

                    <label>Address:</label>
                    <input type="text" v-model="formData.address">

                    <label>CNP:</label>
                    <input type="text" v-model="formData.cnp">

                    <label>Birthdate:</label>
                    <input type="date" v-model="formData.birthdate" required>

                    <label>Role:</label>
                    <select v-model="formData.role">
                        <option value="0">User</option>
                        <option value="1">Doctor</option>
                        <option value="2">Assistant</option>
                        <option value="3">Admin</option>
                    </select>
                <button type="submit">{{ editing ? 'Update' : 'Add' }}</button>
                <button @click="showModal=false">Close</button>
            </form>
                
            </div>
        </div>




        <div class="modal" v-if="showDeleteModal">
            <div class="modal-content">
                <h1>Delete user</h1>
                Do you want to delete user?
                <button @click="deleteUser(this.deleteId)" >Yes</button>
                <button @click="showDeleteModal=false; deleteId=''">No</button>
            </div>
        </div>


      
      
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
      return {
          users: [],
          formData: {
                name: '',
                email: '',
                password:'',
                phone: '',
                address: '',
                cnp: '',
                birthdate: '',
                role: 0,
                deleteId:''
          },
          editing: false,
          editingId: null,
          showModal:false,
          showDeleteModal:false
      }
  },
  methods: {
      fetchUsers() {
          axios.get('/api/admin/users')
              .then(response => {
                  this.users = response.data;
              })
              .catch(error => {
                  console.error('Error fetching users:', error);
              });
      },
      showAddModal(){
          this.showModal=true;
          this.editing=false;
          this.formData = { 
                name: '',
                email: '',
                password:'',
                phone: '',
                address: '',
                cnp: '',
                birthdate: '',
                role: 0 };
      },
      submitForm() {
          if (this.editing) {
              this.updateUser();
          } else {
              this.addUser();
          }
      },
      addUser() {
          axios.post('/api/admin/users', this.formData)
              .then(response => {
                  this.users.push(response.data);
                  this.formData = { name: '', email: '' };
                  this.showModal=false;
                  alert('added');
              })
              .catch(error => {
                  console.error('Error adding user:', error);
              });
      },
      editUser(user) {
          this.showModal=true;
          this.editing = true;
          
          this.formData.name = user.name;
          this.formData.email = user.email;
          this.formData.phone = user.phone;
          this.formData.address = user.address;
          this.formData.cnp = user.cnp;
          this.formData.birthdate = user.birthdate;
          this.formData.role = user.role;
          this.editingId = user.id;
          
          
          
          
      },
      updateUser() {
          axios.put(`/api/admin/users/${this.editingId}`, this.formData)
              .then(response => {
                  const index = this.users.findIndex(user => user.id === this.editingId);
                  if (index !== -1) {
                      this.users[index] = response.data;
                  }
                  this.formData = { name: '', email: '' };
                  this.editing = false;
                  this.editingId = null;
                  this.showModal=false;
                  alert('udated');
              })
              .catch(error => {
                  console.error('Error updating user:', error);
              });
      },
      deleteUser(id) {
          axios.delete(`/api/admin/users/${id}`)
              .then(() => {
                  this.users = this.users.filter(user => user.id !== id);
                  this.showDeleteModal=false;
                  this.deleteId='';
              })
              .catch(error => {
                  console.error('Error deleting user:', error);
              });
      }
  },
  mounted() {
      this.fetchUsers();
  }
}
</script>
<style>
.modal {
    display: block;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 5px;
    position: relative;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

</style>


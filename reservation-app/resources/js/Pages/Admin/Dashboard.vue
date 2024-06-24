<script setup>
import { ref, reactive, watchEffect } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import EditUserModal from '@/Components/EditUserModal.vue';
import NewDatesModal from '@/Components/AdminNewDatesModal.vue';
import axios from 'axios';
import Pagination from "@/Components/Pagination.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

var props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    filters: String
});
let showModal = ref(false);
let modalTitle = ref('');
let selectedUser = ref(null);
let modalMode = ref('');
let search = ref(props.filters);
let showNewDatesModal = ref(false);
const openModal = (mode, user) => {
    showModal.value = true;
    modalMode.value = mode;
    selectedUser.value = { ...user };
    modalTitle.value = mode === 'edit' ? 'Edit User' : 'Delete User';
    document.body.classList.add("modal-open");
};
function openNewDatesModal(){
    showNewDatesModal.value=true;
    document.body.classList.add("modal-open");
}

let filterUsers = async () => {
    router.get('dashboard', { search: search.value }, {
        preserveState: true,
        replace: true
    });
};

const closeModal = () => {
    showModal.value = false;
    selectedUser.value = null;
    document.body.classList.remove("modal-open");
};
let closeNewDatesModal = () => {
    showNewDatesModal.value = false;
    document.body.classList.remove("modal-open");
};
const saveUser = async (updatedUser) => {
    try {
        await axios.post('user/update', updatedUser);
        const index = props.users.data.findIndex(user => user.id === updatedUser.id);
        if (index !== -1) {
            props.users.data[index] = updatedUser;
        }
        closeModal();
        toast.success("User updated successfully !", {
            autoClose: 3000,
            position: toast.POSITION.BOTTOM_RIGHT,
        });
        
    } catch (error) {
        toast.error("Error updating user !", {
            autoClose: 3000,
            position: toast.POSITION.BOTTOM_RIGHT,
        });
    }
};

const deleteUser = async (deletedUser) => {
    try {
        await axios.delete('user/destroy', { data: { id: deletedUser.id } });
        props.users.data = props.users.data.filter(user => user.id !== deletedUser.id);
        closeModal();
        toast.success("User deleted successfully !", {
            autoClose: 3000,
            position: toast.POSITION.BOTTOM_RIGHT,
        });
    } catch (error) {
        toast.error("Error deleting user !", {
            autoClose: 3000,
            position: toast.POSITION.BOTTOM_RIGHT,
        });
    }
};
let insertDates = () => {
    closeNewDatesModal();
};
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
            <button @click="openNewDatesModal" class="mr-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">New Dates</button>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <input type="text" v-model="search" @input="filterUsers"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Phone</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="user in users.data" :key="user.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ user.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ user.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ user.phone }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <font-awesome-icon :icon="['fas', 'pencil']"
                                                class="text-gray-500 hover:text-gray-700 cursor-pointer"
                                                @click="openModal('edit', user)" />
                                            <font-awesome-icon :icon="['fas', 'trash']"
                                                class="text-red-500 hover:text-red-700 cursor-pointer"
                                                @click="openModal('delete', user)" />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="md:hidden">
                        <div v-for="user in users.data" :key="user.id" class="bg-white p-4 rounded-lg shadow mb-4">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold">{{ user.name }}</h3>
                                <div class="flex items-center space-x-2">
                                    <font-awesome-icon :icon="['fas', 'pencil']"
                                        class="text-gray-500 hover:text-gray-700 cursor-pointer"
                                        @click="openModal('edit', user)" />
                                    <font-awesome-icon :icon="['fas', 'trash']"
                                        class="text-red-500 hover:text-red-700 cursor-pointer"
                                        @click="openModal('delete', user)" />
                                </div>
                            </div>
                            <p class="text-sm"><strong>Email:</strong> {{ user.email }}</p>
                            <p class="text-sm"><strong>Phone:</strong> {{ user.phone }}</p>
                        </div>
                    </div>
                    <Pagination :pagination="users.links"></Pagination>
                </div>
            </div>
        </div>
        <Transition>
            <edit-user-modal  :show="showModal" :title="modalTitle" :user="selectedUser" :mode="modalMode"
            @close="closeModal" @save="saveUser" @delete="deleteUser">
        </edit-user-modal>
        </Transition>
        <Transition>
            <new-dates-modal  :show="showNewDatesModal" :title="'Insert new dates'" @close="closeNewDatesModal"
            @save="insertDates">
        </new-dates-modal>
        </Transition>
        
    </AuthenticatedLayout>
</template>
<style>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
body.modal-open {
  overflow: hidden;
}
</style>

<template>
    <div v-if="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center"
        @click.self="close">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 h-screen overflow-auto" v-if="mode === 'edit'">
            <h2 class="text-xl font-bold mb-4">{{ title }}</h2>
            <form  @submit.prevent="save">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name:</label>
                    <input v-model="user.name" type="text"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email:</label>
                    <input v-model="user.email" type="email"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Phone:</label>
                    <input v-model="user.phone" type="text"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Address:</label>
                    <input v-model="user.address" type="text"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Birthdate:</label>
                    <input v-model="user.birthdate" type="date"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Identity Number:</label>
                    <input v-model="user.identity_number" type="text"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Role:</label>
                    <multiselect v-model="user.role" :options="roles" :show-labels="false" :allow-empty="false">
                    </multiselect>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="mr-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                    <button @click="close" type="button"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</button>
                </div>
            </form>
            
        </div>
        <div v-else class="bg-white p-6 rounded-lg shadow-lg w-96 overflow-auto">
            <h2 class="text-xl font-bold mb-4">{{ title }}</h2>
            <div>
                <p>Are you sure you want to delete {{ user.name }}?</p>
                <div class="flex justify-end">
                    <button @click="deleteUser"
                        class="mr-2 bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Delete</button>
                    <button @click="close"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },
    props: ['show', 'title', 'user', 'mode'],
    data() {
        return {
            value: '',
            roles: ['admin', 'user', 'guest']
        };
    },
    methods: {
        close() {
            this.$emit('close');
        },
        save() {
            this.$emit('save', this.user);
        },
        deleteUser() {
            this.$emit('delete', this.user);
        }
    }
};
</script>

<style scoped></style>

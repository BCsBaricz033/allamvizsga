<template>
    <div v-if="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center"
        @click.self="close">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 h-screen overflow-auto">
            <h2 class="text-xl font-bold mb-4">{{ title }}</h2>
            <form @submit.prevent="save">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Institution</label>
                    <input v-model="date.institution_name" type="text" disabled
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Section</label>
                    <input v-model="date.section_name" type="text" disabled
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Doctor</label>
                    <input v-model="date.doctor_name" type="text" disabled
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Patient</label>
                    <input v-model="user.name" type="text" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Begin</label>
                    <input v-model="date.start_time" type="text" disabled
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">End</label>
                    <input v-model="date.end_time" type="text" disabled
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Reason</label>
                    <input v-model="reason" type="text"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Identity</label>
                    <input v-model="user.identity_number" type="text"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Birthdate</label>
                    <input v-model="user.birthdate" type="date" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input v-model="user.phone" type="text" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="user.email" type="email" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="flex justify-end" v-if="mode === 'details'">
                    <button @click="close" type="button"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</button>
                </div>
            </form>
            <div v-if="mode === 'delete'">
                <p>Are you sure you want to reserve this appointment?</p>
                <div class="flex justify-end">
                    <button @click="reserveDate"
                        class="mr-2 bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Reserve</button>
                    <button @click="close"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Multiselect from 'vue-multiselect';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
export default {
    components: {
        Multiselect
    },
    props: ['show', 'title', 'date', 'mode', 'user'],
    data() {
        return {
            reason: '',
            user:{
                name:'',
                identity_number:'',
                birthdate:'',
                phone:'',
                email:''
            }
        };
    },
    methods: {
        close() {
            this.$emit('close');
        },
        async reserveDate() {
            try {
                let insertingresponse = await axios.post('reserve_without_login/reserve', { id: this.date.id, reason: this.reason,
                    name:this.user.name,email:this.user.email,phone:this.user.phone
                 });
                if (insertingresponse.data.success) {
                    toast.success("Date reserved successfully!", {
                        autoClose: 3000,
                        position: toast.POSITION.BOTTOM_RIGHT,
                    });
                    this.$emit('reserve',this.date);
                }
            } catch (error) {
                if (error.response && error.response.data && error.response.data.error) {
                    toast.error(error.response.data.error, {
                        autoClose: 3000,
                        position: toast.POSITION.BOTTOM_RIGHT,
                    });
                } else {
                    toast.error('An unexpected error occurred.', {
                        autoClose: 3000,
                        position: toast.POSITION.BOTTOM_RIGHT,
                    });
                }
            }




        }
    }
};
</script>

<style scoped></style>

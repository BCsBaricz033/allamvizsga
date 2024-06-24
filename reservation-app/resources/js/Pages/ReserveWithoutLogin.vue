<script setup>
import { ref, watch, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import Pagination from "@/Components/Pagination.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import Multiselect from 'vue-multiselect';
import "vue-multiselect/dist/vue-multiselect.css";
import DatesModal from '@/Components/ReserveDatesWithoutLoginModal.vue';
const props = defineProps({
    institutions: Object,
    sections: Object,
    doctors: Object,
});
const institutions = ref(props.institutions);
const sections = ref(props.sections);
const doctors = ref(props.doctors);
const selectedInstitutions = ref([]);
const selectedSections = ref([]);
const selectedDoctors = ref([]);
const selectedPatients = ref(props.patients);
const from = ref(null);
const to = ref(null);
const reserved = ref(false);
const dates = ref([]);

watch(selectedInstitutions, () => {
    refreshSections();
    refreshDoctors();
});

watch(selectedSections, () => {
    refreshDoctors();
});
async function refreshSections() {
    if (selectedInstitutions.value.length < 1) {
        sections.value = props.sections;
    } else {
        let institutionIds = selectedInstitutions.value;
        if (!Array.isArray(institutionIds)) {
            institutionIds = [institutionIds];
        }
        const sectionsResponse = await axios.get('sections', {
            params: {
                institutionIds: institutionIds.map(institution => institution.id)
            }
        });
        sections.value = sectionsResponse.data;
    }
    selectedSections.value = [];
}

async function refreshDoctors() {
    let institutionIds = selectedInstitutions.value;
    let sectionIds = selectedSections.value;
    if (!Array.isArray(institutionIds)) {
        institutionIds = [institutionIds];
    }
    if (!Array.isArray(sectionIds)) {
        sectionIds = [sectionIds];
    }
    const doctorsResponse = await axios.get('doctors', {
        params: {
            institutionIds: institutionIds.map(institution => institution.id),
            sectionIds: sectionIds.map(section => section.id)
        }
    });

    doctors.value = doctorsResponse.data;
    selectedDoctors.value = [];
}

async function filter() {
    let institutionIds = selectedInstitutions.value;
    let sectionIds = selectedSections.value;
    let doctorIds = selectedDoctors.value;
    if (!Array.isArray(institutionIds)) {
        institutionIds = [institutionIds];
    }
    if (!Array.isArray(sectionIds)) {
        sectionIds = [sectionIds];
    }
    if (!Array.isArray(doctorIds)) {
        doctorIds = [doctorIds];
    }
    const datesResponse = await axios.get('get_reserve_filtered_dates', {
        params: {
            institutionIds: institutionIds.map(institution => institution.id),
            sectionIds: sectionIds.map(section => section.id),
            doctorIds: doctorIds.map(doctor => doctor.id),
            from: from.value,
            to: to.value,
            reserved: reserved.value ? 1 : 0,

        }
    });

    dates.value = datesResponse.data;
    if (dates.value.data < 1) {
        toast.info("There are no results for your search", {
            autoClose: 3000,
            position: toast.POSITION.BOTTOM_RIGHT,
        });
    }
}


onMounted(() => {

});


let showDateModal = ref(false);
let modalTitle = ref('');
let selectedDate = ref(null);
let modalMode = ref('');
const openDateModal = (mode, date) => {
    showDateModal.value = true;
    modalMode.value = mode;
    selectedDate.value = { ...date };
    modalTitle.value = mode === 'details' ? 'Details' : 'Reserve date';
    document.body.classList.add("modal-open");
};
const closeDateModal = () => {
    showDateModal.value = false;
    selectedDate.value = null;
    document.body.classList.remove("modal-open");
};
const reserveDate = async (reservedDate) => {
    dates.value.data = dates.value.data.filter(date => date.id !== reservedDate.id);
    closeDateModal();
};
</script>

<template>

    <Head title="Dashboard" />

    
        

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white  shadow-sm sm:rounded-lg">
                    <div class=" p-6 rounded-lg  w-96">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reserve</h2>

                        <form @submit.prevent="filter">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Institution(s)</label>
                                <multiselect v-model="selectedInstitutions" :options="institutions" :multiple="true"
                                    :close-on-select="false" :clear-on-select="false" :preserve-search="true" :limit="1"
                                    label="name" track-by="name" :allow-empty="true">
                                </multiselect>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Section(s)</label>
                                <multiselect v-model="selectedSections" :options="sections" :multiple="true"
                                    :close-on-select="false" :clear-on-select="false" :preserve-search="true" :limit="1"
                                    label="name" track-by="name" :allow-empty="true">
                                </multiselect>

                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Doctor(s)</label>
                                <multiselect v-model="selectedDoctors" :options="doctors" :multiple="true"
                                    :close-on-select="false" :clear-on-select="false" :preserve-search="true" :limit="1"
                                    label="name" track-by="name" :allow-empty="true">
                                </multiselect>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">From</label>
                                <input v-model="from" type="datetime-local"
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">To</label>
                                <input v-model="to" type="datetime-local"
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="mr-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-12" v-if="dates && dates.data && dates.data.length > 0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Doctor</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Patient</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Begin</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        End</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="date in dates.data">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ date.doctor_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ date.patient_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ date.start_time }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ date.end_time }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <font-awesome-icon
                                            :icon="['fas', 'clipboard-check']"
                                                class="text-green-500 hover:text-green-700 cursor-pointer"
                                                @click="openDateModal('delete', date)" />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="md:hidden">
                        <div v-for="date in dates.data" class="bg-white p-4 rounded-lg shadow mb-4">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold"></h3>
                                <div class="flex items-center space-x-2">
                                    <font-awesome-icon 
                                     :icon="['fas', 'clipboard-check']"
                                        class="text-green-500 hover:text-green-700 cursor-pointer"
                                        @click="openDateModal('delete', date)" />
                                </div>
                            </div>
                            <p class="text-sm"><strong>Doctor</strong> {{ date.doctor_name }}</p>
                            <p class="text-sm"><strong>Patient</strong> {{ date.patient_name }}</p>
                            <p class="text-sm"><strong>Begin</strong> {{ date.start_time }}</p>
                            <p class="text-sm"><strong>End</strong> {{ date.end_time }}</p>
                        </div>
                    </div>
                    <Pagination v-if="dates && dates.links" :pagination="dates.links"></Pagination>


                </div>
            </div>

        </div>
        <Transition>
            <dates-modal :show="showDateModal" :title="modalTitle" :date="selectedDate" :mode="modalMode"
                @close="closeDateModal" @reserve="reserveDate">
            </dates-modal>
        </Transition>
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

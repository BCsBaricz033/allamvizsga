<template>
    <div v-if="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center"
        @click.self="close">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 h-screen overflow-auto">
            <h2 class="text-xl font-bold mb-4">{{ title }}</h2>
            <form @submit.prevent="save">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Institution(s)</label>
                    <multiselect v-model="selectedInstitutions" :options="institutions" :multiple="true"
                        :close-on-select="false" :clear-on-select="false" :preserve-search="true" :limit="1"
                        label="name" track-by="name" :preselect-first="true" :allow-empty="false">
                    </multiselect>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Section(s)</label>
                    <multiselect v-model="selectedSections" :options="sections" :multiple="true"
                        :close-on-select="false" :clear-on-select="false" :preserve-search="true" :limit="1"
                        label="name" track-by="name" :preselect-first="true" :allow-empty="false">
                    </multiselect>

                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Doctor(s)</label>
                    <multiselect v-model="selectedDoctors" :options="doctors" :multiple="true" :close-on-select="false"
                        :clear-on-select="false" :preserve-search="true" :limit="1" label="name" track-by="name"
                        :preselect-first="true" :allow-empty="false">

                    </multiselect>

                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Start time</label>
                    <input v-model="startTime" type="datetime-local" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        :min="minDate"
                        :max="maxDate">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">End time</label>
                    <input v-model="endTime" type="datetime-local" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        :min="minDate"
                        :max="maxDate">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Step</label>
                    <input v-model="step" type="number" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="mr-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Insert</button>
                    <button @click="close" type="button"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import Multiselect from 'vue-multiselect';
import { watch } from 'vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
export default {
    components: {
        Multiselect
    },
    props: ['show', 'title'],
    data() {
        return {
            institutions: [],
            sections: [],
            doctors: [],
            selectedInstitutions: [],
            selectedSections: [],
            selectedDoctors: [],
            startTime: this.formatDateTimeLocal(new Date()),
            endTime: this.formatDateTimeLocal(new Date()),
            minDate: this.formatDateTimeLocal(this.getMinDate()),
            maxDate: this.formatDateTimeLocal(this.getMaxDate()),
            step: 1,
        };
    },
    watch: {
        selectedSections() {
            this.refreshDoctors();
        },
    },

    methods: {
        close() {
            this.$emit('close');
        },
        async save() {
            let institutionIds = this.selectedInstitutions;
            let sectionIds = this.selectedSections;
            let doctorIds = this.selectedDoctors;
            if (!Array.isArray(institutionIds)) {
                institutionIds = [institutionIds];
            }
            if (!Array.isArray(sectionIds)) {
                sectionIds = [sectionIds];
            }
            if (!Array.isArray(doctorIds)) {
                doctorIds = [doctorIds];
            }
            const responseforInsertdates = await axios.post('insert_dates', {
                institutionIds: institutionIds.map(institution => institution.id),
                sectionIds: sectionIds.map(section => section.id),
                doctorIds: doctorIds.map(doctor => doctor.id),
                startTime: this.startTime,
                endTime: this.endTime,
                step: this.step,
            });
            this.$emit('save', this.user);
            if (responseforInsertdates.data.success) {
                toast.success("Dates inserted successfully !", {
                    autoClose: 3000,
                    position: toast.POSITION.BOTTOM_RIGHT,
                });
            } else if (responseforInsertdates.data.info) {
                toast.info("Parameters are wrong!", {
                    autoClose: 5000,
                    position: toast.POSITION.BOTTOM_RIGHT,
                });
            }
            else {
                toast.error("Error while inserting dates !", {
                    autoClose: 3000,
                    position: toast.POSITION.BOTTOM_RIGHT,
                });
            }

        },
        async getFormDatas() {
            let result = await axios.get('new_dates');
            this.institutions = result.data.institutions;
            this.selectedInstitutions = this.institutions[0];
            this.sections = result.data.sections;
            this.selectedSections = this.sections[0];
            this.doctors = result.data.doctors;
            this.selectedDoctors = this.doctors[0];

        },
        async refreshSections() {
            let institutionIds = this.selectedInstitutions;
            if (!Array.isArray(institutionIds)) {
                institutionIds = [institutionIds];
            }
            const sectionsResponse = await axios.get('sections', {
                params: {
                    institutionIds:institutionIds.map(institution => institution.id)
                }
            });

            this.sections = sectionsResponse.data;
            this.selectedSections = this.sections[0];
        },
        async refreshDoctors() {
            let institutionIds = this.selectedInstitutions;
            let sectionIds = this.selectedSections;
            if (!Array.isArray(institutionIds)) {
                institutionIds = [institutionIds];
            }
            if (!Array.isArray(sectionIds)) {
                sectionIds = [sectionIds];
            }
            const doctorsResponse = await axios.get('doctors', {
                params: {
                    institutionIds:institutionIds.map(institution => institution.id),
                    sectionIds:sectionIds.map(section => section.id)
                }
            });

            this.doctors = doctorsResponse.data;
            this.selectedDoctors = this.doctors[0];
        },
        formatDateTimeLocal(date) {
            const pad = (n) => n.toString().padStart(2, '0');
            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        },
        getMinDate() {
            const today = new Date();
            return new Date(today.getFullYear(), today.getMonth(), today.getDate());
        },
        getMaxDate() {
            const today = new Date();
            return new Date(today.getFullYear(), today.getMonth() + 2, 0);
        },

    },
    mounted() {
        this.getFormDatas();
    },
};
</script>

<style scoped></style>

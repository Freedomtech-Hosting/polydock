<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    polydockAppType: Object,
    polydockLagoonClusters: Array
})

const form = useForm({
  // Polydock Organisation
  polydock_app_type_id: props.polydockAppType.id,
  
  // Basic Polydock Instance Details
  name: null,
  description: null,

  polydock_lagoon_cluster_id: props.polydockAppType.id,

})

function submit() {
    form.post(route('polydock.instances.create', props.polydockAppType.id))
}

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex items-center"> 
                <img class="float-left w-10 h-10 mr-4 rounded-full" 
                    :src="polydockAppType.icon_path" />
                <h2  class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Launch new {{ polydockAppType.name }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-4 bg-white border-b border-gray-200 lg:p-6 dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent dark:border-gray-700">
                        <form @submit.prevent="submit">
                            <div class="space-y-12 sm:space-y-16">
                                <div>
                                    <div class="pb-12 space-y-8 border-b border-gray-900/10 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
                                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6">
                                            <label for="name" class="block text-sm font-medium leading-6 text-gray-200 sm:pt-1.5">Site Name</label>
                                            <div class="mt-2 sm:col-span-2 sm:mt-0">
                                                <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                                    <input type="text" v-model="form.name" id="name" class="block flex-1 border-0 bg-transparent py-1.5 text-gray-200 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                                </div>
                                                <p class="mt-3 text-sm leading-6 text-gray-200">Give your site a short memerable name. This can be anything and is used to identify your site in the UI dashboard (Max 5 words)</p>
                                                <p class="mt-3 text-sm leading-6 text-red-600" v-if="form.errors.name">{{ form.errors.name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pb-12 space-y-8 border-b border-gray-900/10 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
                                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6">
                                            <label for="description" class="block text-sm font-medium leading-6 text-gray-200 sm:pt-1.5">App Description</label>
                                            <div class="mt-2 sm:col-span-2 sm:mt-0">
                                                <textarea id="description" v-model="form.description" rows="3" class="block w-full max-w-2xl bg-gray-800 rounded-md border-0 py-1.5 text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                <p class="mt-3 text-sm leading-6 text-gray-200">Write a few sentences to describe this site.</p>
                                                <p class="mt-3 text-sm leading-6 text-red-600" v-if="form.errors.description">{{ form.errors.description }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6">
                                        <label for="polydock_lagoon_cluster_id" class="block text-sm font-medium leading-6 text-gray-200 sm:pt-1.5">Jurisdiction</label>
                                        <div class="mt-2 sm:col-span-2 sm:mt-0">
                                            <select v-model="form.polydock_lagoon_cluster_id" id="polydock_lagoon_cluster_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                                <option v-for="cluster in polydockLagoonClusters" :value="cluster.id">
                                                    {{ cluster.name }} ({{ cluster.infra_code }})
                                                </option>
                                            </select>
                                            <p class="mt-3 text-sm leading-6 text-red-600" v-if="form.errors.polydock_lagoon_cluster_id">{{ form.errors.polydock_lagoon_cluster_id }}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="flex items-center justify-end mt-6 gap-x-6">
                                <a :href="route('polydock.apps')" class="text-sm font-semibold leading-6 text-gray-200">Cancel</a>
                                <PrimaryButton type="submit" :disabled="form.processing">Create</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

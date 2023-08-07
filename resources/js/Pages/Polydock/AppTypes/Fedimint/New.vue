<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    polydockAppType: Object,
    polydockLagoonClusters: Array
})
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
                        <p class="mt-2 leading-relaxed text-gray-500 dark:text-gray-400">
                            Coming soon.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
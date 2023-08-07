<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PolydockAppTypesList from '@/Components/PolydockAppTypesList.vue'

defineProps({
    polydockAppTypes: Array
})
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Installable Apps
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-4 bg-white border-b border-gray-200 lg:p-6 dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent dark:border-gray-700">
                        <p class="mt-2 leading-relaxed text-gray-500 dark:text-gray-400">
                            You can create new application instances of the following applications.
                        </p>
                    </div>

                    <div class="p-6 bg-gray-200 bg-opacity-25 dark:bg-gray-800">
                        <PolydockAppTypesList :polydock-app-types="polydockAppTypes" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import PolydockAppInstance from '@/Components/PolydockAppInstance.vue'

defineProps({
    polydockAppInstances: Array
})
</script>

<template>
    <div>
        <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <li v-for="polydockAppInstance in polydockAppInstances" 
                    class="flex flex-col col-span-1 text-center bg-white divide-y divide-gray-200 rounded-lg shadow">
                <PolydockAppInstance :polydock-app-instance="polydockAppInstance" />
            </li>
        </ul>
    </div>
</template>
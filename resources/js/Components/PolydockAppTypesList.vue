<script setup>
import PolydockAppType from '@/Components/PolydockAppType.vue'

defineProps({
    polydockAppTypes: Array
})
</script>

<template>
    <div>
        <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <li v-for="polydockAppType in polydockAppTypes" class="col-span-1 bg-white divide-y divide-gray-200 rounded-lg shadow">
                <PolydockAppType :polydock-app-type="polydockAppType" />
            </li>
        </ul>
    </div>
</template>
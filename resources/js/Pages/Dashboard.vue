<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {onMounted, onUnmounted, ref} from "vue";

const props = defineProps({
    stats: Array
})

const timerId = ref();

onMounted(() => {
    timerId.value = setInterval(function() { pollStats() }, 4500);
})

onUnmounted(() => {
    clearInterval(timerId.value);
})

function pollStats() {
    axios.get(route('polydock.poll.dashboard.stats')).then(function(response) {
        for(var statIndex in response.data.stats) {
            let newStat = response.data.stats[statIndex];
            for(var pStatIndex in props.stats) {
                if(props.stats[pStatIndex].name == newStat.name) {
                  props.stats[pStatIndex].name = newStat.name;
                  props.stats[pStatIndex].value = newStat.value;
                  props.stats[pStatIndex].index = newStat.index;
                }
            }
        }
    });
}

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-4 bg-white border-b border-gray-200 lg:p-6 dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent dark:border-gray-700">
                        <div class="mx-auto max-w-7xl">
                            <div class="grid grid-cols-1 gap-px bg-white/5 sm:grid-cols-2 lg:grid-cols-3">
                                <div v-for="stat in stats" :key="stat.name" class="px-4 py-6 bg-gray-900 sm:px-6 lg:px-8">
                                    <p class="text-sm font-medium leading-6 text-gray-400">{{ stat.name }}</p>
                                    <p class="flex items-baseline mt-2 gap-x-2">
                                        <span class="text-4xl font-semibold tracking-tight text-white">{{ stat.value }}</span>
                                        <span v-if="stat.unit" class="text-sm text-gray-400">{{ stat.unit }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>
import PolydockAppInstanceStatus from '@/Components/PolydockAppInstanceStatus.vue'
import PolydockLagoonClusterFlag from '@/Components/PolydockLagoonClusterFlag.vue'

defineProps({
    polydockAppInstance: Object
})
</script>

<template>
    <div>
        <a :href="route('polydock.instances.view', polydockAppInstance.id)">
            <div class="flex flex-col flex-1 p-8">
                <img class="flex-shrink-0 w-20 h-20 mx-auto rounded-full" 
                    :src="polydockAppInstance.polydock_app_type.icon_path" alt="">
                <p class="mt-2 text-sm text-gray-800">{{ polydockAppInstance.polydock_app_type.name }}</p>
                <div class="flex flex-col items-center">
                    <PolydockLagoonClusterFlag :country="polydockAppInstance.polydock_lagoon_cluster.country_code" :name="polydockAppInstance.polydock_lagoon_cluster.name" />
                </div>    
                <h3 class="mt-4 text-sm font-medium text-gray-900">{{ polydockAppInstance.name }}</h3>
                <dl class="flex flex-col justify-between flex-grow mt-1">
                    <dt class="sr-only">Description</dt>
                    <dd class="text-sm text-gray-500">{{ polydockAppInstance.description }}</dd>
                    <dt class="sr-only">Status</dt>
                    <dd class="mt-3">
                        <PolydockAppInstanceStatus :polydock-app-instance="polydockAppInstance" />
                    </dd>
                </dl>
                
            </div>
        </a>
    </div>
</template>
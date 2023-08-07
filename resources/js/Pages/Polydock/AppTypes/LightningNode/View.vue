<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import { reactive, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import PolydockLagoonClusterFlag from '@/Components/PolydockLagoonClusterFlag.vue'
import PolydockAppInstanceStatus from '@/Components/PolydockAppInstanceStatus.vue'



import { ArrowTopRightOnSquareIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    polydockAppInstance: Object,
})

const lndVars = ref({
    'polydock_var_lnd_alias': null,
    'polydock_var_bitcoin_network': null,
    'polydock_var_lnd_wallet_password': null,
});

for(var vIdx in props.polydockAppInstance.variables) {
    switch(props.polydockAppInstance.variables[vIdx].key) {
        case "LND_ALIAS":
            lndVars.value['polydock_var_lnd_alias'] = props.polydockAppInstance.variables[vIdx].value;
            break;
        case "BITCOIN_NETWORK":
           lndVars.value['polydock_var_bitcoin_network'] = props.polydockAppInstance.variables[vIdx].value;
           break;
        case "LND_WALLETPASSWORD":
           lndVars.value['polydock_var_lnd_wallet_password'] = props.polydockAppInstance.variables[vIdx].value;
            break;
    }
}

const confirmIsVisible = ref(false);

function showConfirm() {
    confirmIsVisible.value = true;
}

function hideConfirm() {
    confirmIsVisible.value = false;
}

function processRemove() {
    confirmIsVisible.value = false;
    router.post(route('polydock.instances.remove', props.polydockAppInstance.id));
}

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex items-center"> 
                <img class="float-left w-10 h-10 mr-4 rounded-full" 
                    :src="polydockAppInstance.polydock_app_type.icon_path" />
                <h2  class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {{ polydockAppInstance.polydock_app_type.name }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-4 border-white/10">
                        <dl class="divide-y divide-white/10">
                            
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Name</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                    {{ polydockAppInstance.name }} 
                                    <span class="float-right">
                                        <PolydockAppInstanceStatus :polydock-app-instance="polydockAppInstance" />
                                    </span>
                                </dd>
                            </div>

                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Description</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">{{ polydockAppInstance.description }}</dd>
                            </div>

                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Created</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">{{ polydockAppInstance.created_at }}</dd>
                            </div>

                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Jurisdiction</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                    <PolydockLagoonClusterFlag :country="polydockAppInstance.polydock_lagoon_cluster.country_code" :name="polydockAppInstance.polydock_lagoon_cluster.name" />
                                </dd>
                            </div>

                            <div v-if="polydockAppInstance.status != 'removed'" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Links</dt>
                                <dd class="mt-2 text-sm text-white sm:col-span-2 sm:mt-0">
                                    <ul v-if="polydockAppInstance.lagoon_routes_json" role="list" class="border divide-y rounded-md divide-white/10 border-white/20">
                                        <li v-for="route in polydockAppInstance.lagoon_routes_json" class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                            <div class="flex items-center flex-1 w-0">
                                                <a :href="route" target="_new">
                                                    <ArrowTopRightOnSquareIcon class="flex-shrink-0 w-5 h-5 text-gray-400" aria-hidden="true" />
                                                </a>
                                                <div class="flex flex-1 min-w-0 gap-2 ml-4">
                                                    <span class="font-medium truncate">
                                                        <a :href="route" target="_new">{{ route }}</a>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 ml-4">
                                                <a :href="route" target="_new" class="font-medium text-indigo-400 hover:text-indigo-300">Open</a>
                                            </div>
                                        </li>
                                    </ul>
                                    <div v-if="! polydockAppInstance.lagoon_routes_json">
                                        Links for your app will listed here when available.
                                    </div>
                                </dd>
                            </div>

                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Lightning Node Alias</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                    {{ lndVars.polydock_var_lnd_alias }}
                                </dd>
                            </div>

                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-white">Bitcoin Network</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-400 sm:col-span-2 sm:mt-0">
                                    {{ lndVars.polydock_var_bitcoin_network }}
                                </dd>
                            </div>

                        </dl>
                    </div>
                    <div v-if="polydockAppInstance.status == 'running'" class="p-4 border-white/10">
                        <div class="flex flex-row-reverse">
                            <DangerButton @click.native="showConfirm">Remove</DangerButton>
                        </div>
                        <ConfirmationModal :show="confirmIsVisible">
                            <template #title>
                                Are you sure you want to delete {{ polydockAppInstance.name }}?
                            </template>
                            <template #footer>
                                <div class="flex flex-row">
                                    <SecondaryButton @click.native="hideConfirm" class="mr-2">Cancel</SecondaryButton>
                                    <DangerButton @click.native="processRemove">Remove</DangerButton>
                                </div>
                            </template>
                        </ConfirmationModal>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

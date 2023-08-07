<script setup>
    const props = defineProps({
        polydockAppType: Object
    })

    const newLink = route('polydock.instances.new', props.polydockAppType.id);
</script>

<template>
    <div>
        <div class="flex items-center justify-between w-full p-6 space-x-6">
            <div class="flex-1 truncate">
                <div class="flex items-center space-x-3">
                    <h3 class="text-sm font-medium text-gray-900 truncate">{{ polydockAppType.name }}</h3>
                </div>
                <p class="mt-1 text-sm text-gray-500 truncate">{{ polydockAppType.description }}</p>
            </div>
            <img class="flex-shrink-0 w-10 h-10 bg-gray-300 rounded-full" 
                 :src="polydockAppType.icon_path" />
        </div>
        <div>
            <div class="flex -mt-px divide-x divide-gray-200">
                
                <div class="flex flex-1 w-0 -ml-px">
                    <a :href="newLink" class="relative inline-flex items-center justify-center flex-1 w-0 py-4 text-sm font-semibold text-gray-900 border border-transparent rounded-br-lg gap-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Launch New
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
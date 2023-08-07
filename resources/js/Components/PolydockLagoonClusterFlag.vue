<script setup>
import {ref} from "vue";

const props = defineProps({
    country: String,
    name: String
})

const flag=ref("/polydock-assets/flags-square/" + props.country + ".png")
</script>

<template>
    <div class="flex items-center">
        <img :src="flag" class="w-8" />
        <span v-if="name" class="ml-2 text-sm">{{ name }}</span>
    </div>
</template>
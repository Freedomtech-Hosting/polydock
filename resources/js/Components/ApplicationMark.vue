<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const polydockLogoPath = computed(() => page.props.polydockLogoPath)

</script>

<template>
    <img :src="polydockLogoPath" />
</template>

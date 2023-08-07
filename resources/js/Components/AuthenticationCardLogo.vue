<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    polydockLogoPath: String
});

</script>

<template>
    <Link :href="'/'">
        <img :src="polydockLogoPath" class="w-40" />
    </Link>
</template>

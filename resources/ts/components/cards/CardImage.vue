<template>
    <figure>
        <img :src="src">
    </figure>
</template>
<script setup lang="ts">
defineProps({
    src: {type: String, required :true}
})
</script>


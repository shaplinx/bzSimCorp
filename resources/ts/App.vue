<script setup lang="ts">
import { ModalsContainer } from 'vue-final-modal'
import { RouterView } from 'vue-router'
import 'vue-final-modal/style.css'
</script>

<template>
    <div>
        <ModalsContainer />
        <RouterView />
    </div>
</template>

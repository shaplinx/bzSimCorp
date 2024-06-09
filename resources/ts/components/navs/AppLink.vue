<template>
    <a v-if="isExternalLink" v-bind="$attrs" :href="to" target="_blank">
        <slot />
    </a>
    <router-link v-else v-bind="$props" custom v-slot="{ isActive, href, navigate, isExactActive }">
        <a v-bind="$attrs" :href="href" @click="customNavigate" :class="[
            isActive ? activeClass : inactiveClass,
            isExactActive ? exactActiveClass : exactInactiveClass,
        ]">
            <slot :isExternalLink="isExternalLink" :href="href" :navigate="customNavigate" :isActive="isActive"
                :isExactActive="isExactActive"> </slot>
        </a>
    </router-link>
</template>

<script lang="ts">
import { computed, watch } from 'vue';
import { RouterLink, useLink } from 'vue-router';
import { hideAllPoppers } from 'floating-vue'



export default {
    name: 'AppLink',

    props: {
        //@ts-ignore
        ...RouterLink.props,
        inactiveClass: String,
        exactActiveClass: String,
        exactInactiveClass: String,

    },



    setup(props, { emit }) {

        const isExternalLink = computed(
            () => typeof props.to === 'string' && props.to.startsWith('http')
        )
        //@ts-ignore
        const { route, href, isActive, isExactActive, navigate } = useLink(props)

        function customNavigate(p? :any) {
            return new Promise((reslove, reject) => {
                navigate(p).then((e) => {
                    hideAllPoppers();
                    reslove(e);
                }).catch((e) => {
                    reject(e);
                })
            })
        }
        watch(isActive, () => {
            emit('onActiveChange', isExactActive.value)
        })


        return { isExternalLink, route, href, isActive, isExactActive, navigate, customNavigate }
    },





}


</script>

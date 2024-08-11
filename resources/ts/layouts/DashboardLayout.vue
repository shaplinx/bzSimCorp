<script setup lang="ts">
import HeaderArea from './HeaderArea.vue'
import SidebarArea from './SidebarArea.vue'

</script>

<template>

    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">

        <!-- ===== Sidebar Start ===== -->
        <SidebarArea />
        <!-- ===== Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <!-- ===== Header Start ===== -->
            <HeaderArea />


            <!-- ===== Header End ===== -->

            <!-- ===== Main Content Start ===== -->
            <main>
                <div class="mx-auto p-4 md:p-6 2xl:p-10 bg-base-300">

                    <RouterView></RouterView>
                </div>
            </main>
            <!-- ===== Main Content End ===== -->
        </div>
    </div>
    <!-- ===== Page Wrapper End ===== -->
</template>

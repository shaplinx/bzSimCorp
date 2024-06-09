<script setup lang="ts">
// @ts-ignore
import jsVectorMap from 'jsvectormap'
import '@/assets/js/us-aea-en'
import { onMounted } from 'vue'
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import { useStylesStore } from '@/stores/styles';

const styles = useStylesStore();

onMounted(() => {
    new jsVectorMap({
        map: 'us_aea_en',
        selector: '#mapOne',
        zoomButtons: true,

        regionStyle: {
            initial: {
                fill: styles.colors.neutral
            },
            hover: {
                fillOpacity: 1,
                fill: styles.colors.primary
            }
        },
        regionLabelStyle: {
            initial: {
                fontFamily: 'Satoshi',
                fontWeight: 'semibold',
                fill: '#fff'
            },
            hover: {
                cursor: 'pointer'
            }
        },

        labels: {
            regions: {
                render(code: any) {
                    return code.split('-')[1]
                }
            }
        }
    })
})
</script>

<template>
    <Card
        class="col-span-12 py-6 px-7.5 shadow-default xl:col-span-7" variant="base">
        <CardBody>
            <h4 class="mb-2 text-xl font-bold text-base-content">Region labels</h4>
            <div id="mapOne" class="mapOne map-btn !h-90"></div>
        </CardBody>
    </Card>
</template>

<template>
  <component
    v-if="detailComponent"
    :is="detailComponent"
    :slug="detail"
    :page="page"
  />

  <div v-else class="py-24 text-center">
    Không tìm thấy component detail cho loại: {{ page }}
  </div>
</template>

<script setup lang="ts">

import { computed } from 'vue'
import { useMappedDetailPage } from '~/composables/useMappedDetailPage'
import { useEntitySeo } from '~/composables/seo/useEntitySeo'


const route = useRoute();

const page = computed(() => String(route.params.page || ""));
const detail = computed(() => String(route.params.detail || ""));

const { detailComponent } = useMappedDetailPage(page)
console.log(detailComponent.value);

// Load SEO metadata dynamically based on the detail slug
useEntitySeo(detail.value);

</script>
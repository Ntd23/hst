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
const { page, detail, detailComponent } = useDetailRoutePage();
</script>

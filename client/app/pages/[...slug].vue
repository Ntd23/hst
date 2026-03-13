<template>
  <div v-if="detailComponent" class="detail-page-wrapper">
    <component :is="detailComponent" :slug="slug" :prefix="prefix" />
  </div>
  <div v-else class="min-h-[50vh] flex flex-col items-center justify-center text-center px-4">
    <h1 class="text-3xl font-bold text-slate-800 mb-2">Trang không tồn tại</h1>
    <p class="text-slate-500 mb-6">Xin lỗi, chúng tôi không tìm thấy nội dung chi tiết cho đường dẫn: <span class="font-mono text-primary bg-primary/10 px-2 py-1 rounded">/{{ slug }}</span></p>
    <UButton to="/" color="primary" variant="solid">Về trang chủ</UButton>
  </div>
</template>

<script setup lang="ts">
import { computed, defineAsyncComponent, shallowRef, watchEffect } from "vue";
import { useRoute } from "vue-router";

definePageMeta({ name: 'detail-slug-catchall' })

const route = useRoute();
const slugArray = route.params.slug;

// Ensure it's treated as an array to extract prefix
const slugs = Array.isArray(slugArray) ? slugArray : [slugArray || ''];
const slug = slugs.join('/');

// Lấy phần đầu tiên của URL (vd: "blog" trong "blog/chi-tiet-1")
const prefix = computed(() => slugs.length > 0 ? slugs[0]?.toLowerCase() : '');

// Ánh xạ file tự động. Mapping 'blog' -> 'BlogDetail.vue', 'service' -> 'ServiceDetail.vue'
const detailComponent = shallowRef<any>(null);

watchEffect(() => {
  if (!prefix.value) {
     detailComponent.value = null;
     return;
  }
  
  // Viết hoa chữ cái đầu tiên để map với tên File Component (blog -> BlogDetail)
  const componentName = prefix.value.charAt(0).toUpperCase() + prefix.value.slice(1) + 'Detail';
  
  try {
    // Dynamic import component từ thư mục details
    detailComponent.value = defineAsyncComponent({
      loader: () => import(`~/components/pages/details/${componentName}.vue`),
      // Optional: loading or error fallback can be added here
    });
  } catch (e) {
    console.error(`Không tìm thấy giao diện chi tiết cho loại: ${prefix.value}`, e);
    detailComponent.value = null; // show 404
  }
});

// SEO tự động cho trang chi tiết
const titleSlug = slug ? slug.charAt(0).toUpperCase() + slug.slice(1).replace(/-/g, ' ') : 'Detail Page';
usePageSeo(computed(() => ({
  title: `${titleSlug} | HISOTECH`,
  description: `Nội dung chi tiết của ${titleSlug}`
})))
</script>

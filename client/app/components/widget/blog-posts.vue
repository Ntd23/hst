<template>
  <div class="space-y-5">
    <h4 v-if="content.title" class="text-base font-black tracking-tight text-slate-900">
      {{ content.title }}
    </h4>

    <div class="space-y-4">
      <NuxtLink
        v-for="item in content.items || []"
        :key="item.id"
        :to="item.url || (item.slug ? `/blog/${item.slug}` : '#')"
        class="group flex gap-3"
      >
        <div class="h-16 w-16 shrink-0 overflow-hidden rounded-lg bg-slate-100">
          <NuxtImg
            v-if="item.image"
            :src="item.image"
            :alt="item.name"
            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
          />
        </div>
        <div>
          <h5 class="line-clamp-2 text-sm font-bold text-slate-800 transition-colors group-hover:text-primary">
            {{ decodeHtml(item.name) }}
          </h5>
          <div v-if="item.created_at" class="mt-1 text-xs text-slate-400">
            {{ formatDate(item.created_at) }}
          </div>
        </div>
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{ data?: any }>();
const content = computed(() => props.data || {});
const { formatDate } = useCommonCardText();
<<<<<<< HEAD
const { decodeHtml } = useDecodeHtml();
=======
>>>>>>> origin/main
</script>

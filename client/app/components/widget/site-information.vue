<template>
  <div class="space-y-5">
    <NuxtImg
      v-if="content.logo"
      :src="content.logo"
      alt="Footer logo"
      class="h-10 w-auto object-contain"
    />

    <p
      v-if="content.description"
      class="text-sm leading-relaxed text-slate-300"
      v-html="content.description"
    />

    <div v-if="content.items?.length" class="space-y-3">
      <div
        v-for="(item, index) in content.items"
        :key="`${item.title}-${index}`"
        class="flex items-start gap-3"
      >
        <UIcon
          v-if="item.icon"
          :name="toUiIcon(item.icon)"
          class="mt-1 size-4 shrink-0 text-primary"
        />
        <div>
          <p v-if="item.title" class="font-semibold text-white" v-html="item.title" />
          <p
            v-if="item.description"
            class="text-sm leading-relaxed text-slate-400"
            v-html="item.description"
          />
        </div>
      </div>
    </div>

    <div v-if="content.display_social_links" class="flex flex-wrap gap-3">
      <ULink
        v-for="social in content.socials || []"
        :key="social.network"
        :to="social.url"
        target="_blank"
        class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-slate-200 transition hover:bg-primary hover:text-white"
      >
        <span class="text-[10px] font-black uppercase">{{ social.network?.slice(0, 2) }}</span>
      </ULink>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any;
}>();

const content = computed(() => props.data || {});

const toUiIcon = (icon?: string) => {
  if (!icon) return "i-lucide-circle";

  return `i-lucide-${icon.replace(/^ti ti-/, "")}`;
};
</script>

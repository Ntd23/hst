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
        <img
          v-if="item.icon_image"
          :src="item.icon_image"
          :alt="item.title || 'Icon'"
          class="mt-1 h-4 w-4 shrink-0 object-contain"
        />
        <UIcon
          v-else-if="item.icon"
          :name="iconName(item.icon)"
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
        <img
          v-if="social.icon_image"
          :src="social.icon_image"
          :alt="social.label || social.network || 'Social icon'"
          class="h-4 w-4 object-contain"
        />
        <UIcon v-else :name="iconName(social.icon)" class="size-4" />
      </ULink>
    </div>
  </div>
</template>

<script setup lang="ts">
import { iconName } from "~/utils/iconName";

const props = defineProps<{
  data?: any;
}>();

const content = computed(() => props.data || {});
</script>

<template>
  <div
    class="col-span-1 md:col-span-2 lg:col-span-4 overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-6 text-white backdrop-blur-sm"
  >
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
      <div class="max-w-2xl">
        <p
          v-if="content.subtitle"
          class="mb-2 text-xs font-black uppercase tracking-[0.25em] text-primary/90"
          v-html="content.subtitle"
        />
        <h3
          v-if="content.title"
          class="text-xl font-black tracking-tight text-white sm:text-2xl"
          v-html="content.title"
        />
        <p
          v-if="content.description"
          class="mt-3 text-sm leading-relaxed text-slate-300"
          v-html="content.description"
        />
      </div>

      <form
        class="flex w-full max-w-md items-center gap-0 overflow-hidden rounded-2xl border border-white/10 bg-slate-950/40"
        @submit.prevent="handleSubmit"
      >
        <input
          type="email"
          class="min-w-0 flex-1 bg-transparent px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none"
          placeholder="Email address"
        />
        <button
          type="submit"
          class="shrink-0 bg-primary px-4 py-3 text-sm font-bold text-white transition hover:brightness-110"
        >
          Subscribe
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any;
}>();

const content = computed(() => props.data || {});

const handleSubmit = () => {};
</script>

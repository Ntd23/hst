<template>
  <main class="relative overflow-hidden py-16 sm:py-24">
    <div class="absolute inset-0 bg-gradient-to-br from-sky-50 via-white to-cyan-50" />
    <UContainer class="relative z-10">
      <div class="mx-auto grid max-w-6xl gap-8 lg:grid-cols-2">
        <section class="rounded-3xl border border-sky-100 bg-white/90 p-8 shadow-sm sm:p-10">
          <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">
            Contact Page
          </p>
          <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
            {{ heading }}
          </h1>
          <p class="mt-4 text-slate-600">
            This page is rendered by dynamic menu route mapping. You can rename slug in Laravel menu and keep this page.
          </p>

          <div class="mt-8 space-y-4 text-sm text-slate-700">
            <div class="rounded-2xl border border-slate-200 p-4">
              <p class="text-xs uppercase tracking-wide text-slate-500">Resolved path</p>
              <p class="mt-1 font-semibold text-slate-900">{{ resolvedPath }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 p-4">
              <p class="text-xs uppercase tracking-wide text-slate-500">Email</p>
              <p class="mt-1 font-semibold text-slate-900">contact@hisotechgroup.com</p>
            </div>
            <div class="rounded-2xl border border-slate-200 p-4">
              <p class="text-xs uppercase tracking-wide text-slate-500">Phone</p>
              <p class="mt-1 font-semibold text-slate-900">+84 973 73 56 79</p>
            </div>
          </div>
        </section>

        <section class="rounded-3xl border border-sky-100 bg-white/90 p-8 shadow-sm sm:p-10">
          <h2 class="text-xl font-bold text-slate-900">Send a message</h2>
          <form class="mt-6 space-y-4" @submit.prevent>
            <div>
              <label class="mb-1 block text-sm font-medium text-slate-700">Full name</label>
              <input
                type="text"
                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none ring-primary/20 transition focus:border-primary focus:ring-4"
                placeholder="Your name"
              >
            </div>
            <div>
              <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
              <input
                type="email"
                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none ring-primary/20 transition focus:border-primary focus:ring-4"
                placeholder="you@example.com"
              >
            </div>
            <div>
              <label class="mb-1 block text-sm font-medium text-slate-700">Message</label>
              <textarea
                rows="5"
                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none ring-primary/20 transition focus:border-primary focus:ring-4"
                placeholder="How can we help?"
              />
            </div>
            <UButton type="submit" color="primary" variant="solid">
              Submit
            </UButton>
          </form>
        </section>
      </div>
    </UContainer>
  </main>
</template>

<script setup lang="ts">
type MenuItem = {
  title?: string
}

const props = defineProps<{
  menuItem?: MenuItem | null
  resolvedPath: string
}>()

const heading = computed(() => props.menuItem?.title || 'Contact Us')
</script>

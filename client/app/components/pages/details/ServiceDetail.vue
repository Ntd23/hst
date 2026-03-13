<template>
  <main class="max-w-7xl mx-auto px-6 py-12">
    <!-- HERO SECTION -->
    <div class="bg-primary/5 p-10 rounded-2xl mb-12">
      <div class="grid grid-cols-12 gap-10 items-center">
        <!-- LEFT CONTENT -->
        <div class="col-span-12 lg:col-span-6 space-y-6">
          <UBadge color="primary" variant="subtle" size="md" class="mb-4">
            Dịch vụ
          </UBadge>
          <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight text-slate-900" v-html="data?.name">
          </h1>
          <p class="text-lg text-slate-600 leading-relaxed" v-html="data?.description">
          </p>
        </div>

        <!-- RIGHT IMAGE -->
        <div class="col-span-12 lg:col-span-6">
          <div class="rounded-2xl overflow-hidden shadow-xl aspect-video bg-slate-100 relative">
             <NuxtImg v-if="data?.image" :src="data.image" class="w-full h-full object-cover" />
             <div v-else class="absolute inset-0 flex items-center justify-center text-slate-300">
                <UIcon name="i-lucide-image" class="w-16 h-16" />
             </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CONTENT SECTION -->
    <div class="grid grid-cols-12 gap-12 mt-16">
      <article
        class="col-span-12 lg:col-span-8 space-y-6 text-lg leading-relaxed pe-5 prose prose-slate max-w-none"
        v-html="data?.content"
      >
      </article>

      <!-- SIDEBAR -->
      <aside class="col-span-12 lg:col-span-4 rounded-2xl bg-slate-50 p-6 border border-slate-100 h-fit sticky top-24">
        <h3 class="font-bold text-xl mb-6 text-slate-900">Liên hệ tư vấn</h3>
        <p class="text-sm text-slate-500 mb-6">Để lại thông tin, chuyên viên của chúng tôi sẽ liên hệ tư vấn giải pháp phù hợp nhất cho doanh nghiệp của bạn.</p>
        
        <form class="space-y-4">
           <UInput placeholder="Họ và tên" />
           <UInput placeholder="Số điện thoại" />
           <UInput placeholder="Email" />
           <UTextarea placeholder="Nội dung cần tư vấn..." />
           <UButton block color="primary">Gửi yêu cầu</UButton>
        </form>
      </aside>
    </div>
  </main>
</template>
<script setup lang="ts">
const props = defineProps<{
  slug: string;
  prefix: string;
  data: any;
}>();
</script>

<template>
  <main class="relative w-full bg-pastel-gradient">
    <div v-if="pending" class="flex min-h-[60vh] items-center justify-center">
      <UIcon name="i-lucide-loader-2" class="size-8 animate-spin text-primary" />
    </div>

    <template v-else-if="pageData">
      <section class="pt-8 pb-16">
        <UContainer>
          <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-2">Chi tiết dịch vụ</h1>
            <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
              <NuxtLink to="/" class="hover:text-primary transition-colors">Trang chủ</NuxtLink>
              <UIcon name="i-lucide-chevron-right" class="size-4 text-slate-300" />
              <span class="text-primary font-medium">{{ pageData.name }}</span>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <aside class="lg:col-span-3 space-y-8">
              <div v-if="sidebarServices.length" class="glass-panel rounded-2xl overflow-hidden p-2">
                <nav class="flex flex-col space-y-1">
                  <NuxtLink
                    v-for="service in sidebarServices"
                    :key="service.id || service.slug"
                    :to="service.slug ? `/services/${service.slug}` : '#'"
                    :class="[
                      'flex items-start justify-between gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors',
                      service.slug === props.slug
                        ? 'bg-primary/10 text-primary border-l-4 border-primary'
                        : 'text-slate-600 hover:bg-slate-50 hover:text-primary'
                    ]"
                  >
                    <span class="line-clamp-2">{{ service.name }}</span>
                    <UIcon name="i-lucide-arrow-right" class="size-4 shrink-0" />
                  </NuxtLink>
                </nav>
              </div>

              <div v-if="handbookItems.length" class="glass-panel rounded-2xl p-6">
                <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-3">
                  <UIcon name="i-lucide-book-open" class="size-5 text-primary" />
                  <h3 class="font-bold text-slate-800">Sổ tay</h3>
                </div>
                <p class="text-sm text-slate-500 mb-4">
                  Bạn có thể đọc thêm các tài liệu dưới đây của chúng tôi
                </p>
                <div class="space-y-2">
                  <a
                    v-for="item in handbookItems"
                    :key="item.label"
                    class="flex items-center gap-3 p-3 rounded-lg bg-blue-50/50 hover:bg-blue-50 transition-colors group"
                    :href="item.url"
                  >
                    <UIcon :name="item.icon" :class="['size-5', item.color]" />
                    <span class="text-sm font-medium text-slate-700">{{ item.label }}</span>
                  </a>
                </div>
              </div>

              <div v-if="recentPosts.length" class="glass-panel rounded-2xl p-6">
                <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-3">
                  <UIcon name="i-lucide-rss" class="size-5 text-primary" />
                  <h3 class="font-bold text-slate-800">Bài đăng mới</h3>
                </div>
                <div class="space-y-4">
                  <NuxtLink
                    v-for="post in recentPosts"
                    :key="post.id"
                    :to="post.slug ? `/blog/${post.slug}` : '#'"
                    class="flex gap-3 group"
                  >
                    <div class="w-16 h-16 rounded-lg bg-slate-200 overflow-hidden shrink-0">
                      <img
                        :src="post.image"
                        :alt="post.name"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                      />
                    </div>
                    <div>
                      <h4 class="text-sm font-bold text-slate-800 line-clamp-2 group-hover:text-primary transition-colors">
                        {{ post.name }}
                      </h4>
                      <div class="flex items-center gap-1 text-xs text-slate-400 mt-1">
                        <UIcon name="i-lucide-calendar" class="size-4" />
                        <span>{{ post.published_at }}</span>
                      </div>
                    </div>
                  </NuxtLink>
                </div>
              </div>

              <div class="rounded-2xl p-6 bg-[#1a237e] text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10 transform translate-x-1/4 -translate-y-1/4">
                  <UIcon name="i-lucide-headset" class="size-24" />
                </div>
                <h3 class="text-xl font-bold mb-2 relative z-10">Nếu bạn cần bất kì sự hỗ trợ nào</h3>
                <p class="text-blue-200 text-sm mb-6 relative z-10">hãy gọi cho chúng tôi ngay bây giờ</p>
                <button
                  class="w-full py-3 rounded-xl bg-primary hover:bg-blue-600 transition-colors flex items-center justify-center gap-2 font-bold shadow-lg shadow-blue-900/50 relative z-10"
                  type="button"
                >
                  <UIcon name="i-lucide-phone" class="size-5" />
                  +84 973 73 56 79
                </button>
              </div>

              <div class="glass-panel rounded-2xl p-6 bg-blue-50/30">
                <h3 class="font-bold text-slate-800 mb-4 border-l-4 border-primary pl-3">Gửi tin nhắn cho chúng tôi</h3>
                <form class="space-y-3">
                  <input
                    type="text"
                    placeholder="Tên Của Bạn"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                  />
                  <input
                    type="email"
                    placeholder="Email Của Bạn"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                  />
                  <textarea
                    rows="4"
                    placeholder="Viết Lời Nhắn Của Bạn Ở Đây"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                  ></textarea>
                  <button
                    type="submit"
                    class="w-full py-3 rounded-xl bg-[#1a237e] text-white text-sm font-bold uppercase tracking-wide hover:bg-blue-900 transition-colors shadow-lg"
                  >
                    Gửi Tin Nhắn
                  </button>
                </form>
              </div>
            </aside>

            <div class="lg:col-span-9 space-y-8">
              <div v-if="pageData.image" class="rounded-3xl overflow-hidden shadow-2xl shadow-blue-900/10">
                <NuxtImg
                  :src="pageData.image"
                  :alt="pageData.name"
                  class="w-full h-72 sm:h-80 object-cover"
                />
              </div>

              <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-primary mb-3">
                  {{ pageData.name }}
                </h2>
                <p v-if="pageData.description" class="text-slate-600 leading-relaxed text-lg">
                  {{ pageData.description }}
                </p>
              </div>

              <template v-if="Shortcodes.length > 0">
                <component
                  v-for="(sc, index) in Shortcodes"
                  :key="index"
                  :is="sc.component"
                  :data="sc.data"
                  v-bind="index >= 3 ? { 'hydrate-on-visible': true } : {}"
                />
              </template>

              <section v-if="cleanContent" class="prose prose-lg prose-slate max-w-none">
                <div v-html="cleanContent" />
              </section>
            </div>
          </div>
        </UContainer>
      </section>
    </template>

    <div v-else class="py-24 text-center">
      <h1 class="text-2xl font-bold text-gray-800">Page Content Not Found</h1>
      <p class="text-gray-500 mt-2">The requested slug "{{ props.slug }}" returned no data from the API.</p>
    </div>
  </main>
</template>

<script setup lang="ts">
import { useMappedShortcodes } from '~/composables/useMappedShortcodes'
import { usePageSections } from '~/composables/usePageSections'

const props = defineProps<{
  slug: string
}>()

const { Shortcodes, mapSectionsToShortcodes } = useMappedShortcodes()
const { data: pageData, pending } = await usePageDetail<any>(props.slug)
const { data: servicesPage } = await usePageSections<any>('services')
const { data: blogListing } = await useBlogListing<any>(
  computed(() => ({
    limit: 3,
  }))
)

// Map shortcode sections khi data sẵn sàng (CSR/SSR)
watch(
  () => pageData.value?.sections,
  (sections) => {
    mapSectionsToShortcodes(sections || [])
  },
  { immediate: true }
)

const sidebarServices = computed(() => {
  const sections = servicesPage.value?.sections || []
  const servicesSection = sections.find((section: any) => section.shortcode === 'services')
  const services = servicesSection?.content?.services || []
  return services.filter((service: any) => service?.slug !== props.slug)
})

const recentPosts = computed(() => blogListing.value?.data?.sidebar?.recent_posts ?? [])

const handbookItems = ref([
  { label: 'document.pdf', icon: 'i-lucide-file-text', color: 'text-red-500', url: '#' },
  { label: 'document.docx', icon: 'i-lucide-file-text', color: 'text-blue-500', url: '#' },
])

// Content HTML sạch (đã strip shortcode tags ở backend)
const cleanContent = computed(() => {
  const content = pageData.value?.content
  if (!content) return null
  const stripped = content
    .replace(/<[^>]*>/g, '')
    .replace(/&nbsp;|&#160;/gi, ' ')
    .replace(/\u00a0/g, ' ')
    .trim()
  return stripped ? content : null
})
</script>

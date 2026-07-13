<template>
  <section class="py-16">
    <UContainer>
      <div
        class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-10 sm:mb-12"
      >
        <CommonsSectionHeading
          :title="sectionData.title"
          :subtitle="sectionData.subtitle"
          align="left"
          compact
        />
        <div class="flex gap-2 mt-1 sm:mt-0">
          <UButton color="neutral" variant="outline" icon="i-lucide-chevron-left" square class="btn-icon-circle btn-icon-circle-outline" />
          <UButton color="primary" variant="solid" icon="i-lucide-chevron-right" square class="btn-icon-circle btn-icon-circle-primary" />
        </div>
      </div>

      <!-- Overlay Showcase: phone=1col, tablet=2col, desktop=3col -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6 lg:gap-8">
        <button
          v-for="(product, i) in products"
          :key="product.id || product.name || i"
          type="button"
          :aria-label="product.name"
          class="group card-hover-glow rounded-2xl overflow-hidden relative cursor-pointer block w-full text-left"
          @click="openPreview(product)"
        >
          <div
            class="webdemo-preview bg-slate-100 dark:bg-slate-800 h-48 sm:h-52 md:h-56 lg:h-64 w-full flex items-center justify-center overflow-hidden relative"
            @mouseenter="startImageScroll"
            @mouseleave="resetImageScroll"
          >
            <template v-if="product.img_full || product.img_featured">
              <NuxtImg
                :src="product.img_full || product.img_featured"
                loading="lazy"
                class="webdemo-preview__image absolute inset-x-0 top-0 z-10 w-full h-auto min-h-full object-cover"
              />
            </template>
            <div v-else class="text-slate-400">{{ $t('common.noImage') }}</div>
          </div>

          <!-- Overlay info -->
          <div class="absolute inset-x-0 bottom-0 p-4 sm:p-5 lg:p-6 bg-gradient-to-t from-slate-900/90 via-slate-900/60 to-transparent opacity-90 sm:opacity-80  group-hover:opacity-100 transition-all duration-300 z-20">
            <div class="flex items-end justify-between">
              <div>
                <h3 class="text-base sm:text-lg font-bold text-white mb-0.5 sm:mb-1 line-clamp-2" v-html="product.name">
                </h3>
              </div>
              <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white shrink-0 ml-2">
                <UIcon name="i-lucide-arrow-up-right" class="size-4 sm:size-5" />
              </div>
            </div>
          </div>
        </button>
      </div>
    </UContainer>

    <UModal
      v-model:open="isPreviewOpen"
      :title="previewTitle"
      class="w-[calc(100vw-1rem)] max-w-[1400px] sm:w-[85vw]"
      :ui="{
        content: 'overflow-hidden rounded-2xl',
        header: 'shrink-0 pr-14',
        body: 'overflow-hidden p-0 sm:p-0',
        footer: 'shrink-0 justify-end'
      }"
      @after:enter="startModalImageScroll"
      @after:leave="resetModalPreview"
    >
      <template #body>
        <div
          ref="modalViewport"
          class="webdemo-modal-preview relative h-[62vh] min-h-80 overflow-hidden bg-slate-100 dark:bg-slate-900"
        >
          <NuxtImg
            v-if="previewImage"
            ref="modalImage"
            :src="previewImage"
            :alt="previewTitle"
            class="webdemo-modal-preview__image absolute inset-x-0 top-0 w-full h-auto"
            @load="startModalImageScroll"
          />
          <div v-else class="flex h-full items-center justify-center text-slate-400">
            {{ $t('common.noImage') }}
          </div>
        </div>
      </template>

      <template #footer>
        <UButton color="neutral" variant="outline" @click="isPreviewOpen = false">
          {{ $t('common.close') }}
        </UButton>
        <UButton
          v-if="selectedProduct?.url_client"
          :to="selectedProduct.url_client"
          target="_blank"
          rel="noopener noreferrer"
          trailing-icon="i-lucide-external-link"
        >
          {{ $t('common.openWebsite') }}
        </UButton>
      </template>
    </UModal>
  </section>
</template>

<style scoped>
section {
  font-family: var(--font-body, sans-serif);
}
h2, h3 {
  font-family: var(--font-tech, sans-serif);
}

.webdemo-preview__image {
  transform: translate3d(0, 0, 0);
  transition-property: transform;
  transition-timing-function: linear;
  will-change: transform;
}

.webdemo-modal-preview__image {
  transform: translate3d(0, 0, 0);
  transition-property: transform;
  transition-timing-function: linear;
  will-change: transform;
}

@media (prefers-reduced-motion: reduce) {
  .webdemo-preview__image,
  .webdemo-modal-preview__image {
    transition: none !important;
    transform: none !important;
  }
}
</style>

<script setup lang="ts">
import CommonsSectionHeading from "~/components/commons/SectionHeading.vue";

type WebdemoProduct = {
  id?: number | string
  name?: string
  url_client?: string
  img_full?: string
  img_featured?: string
}

const props = defineProps<{
  data?: any
}>()

const { sectionData, products } = useIncludeWebdemoShortcode(
  toRef(props, "data")
)

const isPreviewOpen = ref(false)
const selectedProduct = ref<WebdemoProduct | null>(null)
const modalViewport = ref<HTMLElement | null>(null)
const modalImage = ref<{ $el?: HTMLImageElement } | HTMLImageElement | null>(null)
let modalScrollTimer: ReturnType<typeof setTimeout> | undefined

const previewTitle = computed(() => selectedProduct.value?.name || "")
const previewImage = computed(() =>
  selectedProduct.value?.img_full || selectedProduct.value?.img_featured || ""
)

const openPreview = (product: WebdemoProduct) => {
  selectedProduct.value = product
  isPreviewOpen.value = true
}

const getPreviewImage = (container: HTMLElement) =>
  container.querySelector<HTMLImageElement>(".webdemo-preview__image")

const scrollImageToBottom = (container: HTMLElement) => {
  if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return

  const image = getPreviewImage(container)
  if (!image) return

  const distance = Math.max(0, image.offsetHeight - container.clientHeight)
  if (!distance) return

  // Keep a steady reading speed while constraining very short/long screenshots.
  const duration = Math.min(12, Math.max(3, distance / 90))
  image.style.transitionDuration = `${duration}s`
  image.style.transitionTimingFunction = "linear"
  image.style.transform = `translate3d(0, -${distance}px, 0)`
}

const startImageScroll = (event: MouseEvent) => {
  const container = event.currentTarget as HTMLElement
  const image = getPreviewImage(container)
  if (!image) return

  if (image.complete) {
    scrollImageToBottom(container)
    return
  }

  image.addEventListener("load", () => {
    if (container.matches(":hover")) scrollImageToBottom(container)
  }, { once: true })
}

const resetImageScroll = (event: MouseEvent) => {
  const image = getPreviewImage(event.currentTarget as HTMLElement)
  if (!image) return

  image.style.transitionDuration = "1.2s"
  image.style.transitionTimingFunction = "ease-out"
  image.style.transform = "translate3d(0, 0, 0)"
}

const getModalImageElement = () => {
  const image = modalImage.value
  if (!image) return null

  return image instanceof HTMLImageElement ? image : image.$el || null
}

const startModalImageScroll = () => {
  if (!isPreviewOpen.value) return
  if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return

  clearTimeout(modalScrollTimer)
  modalScrollTimer = setTimeout(() => {
    const viewport = modalViewport.value
    const image = getModalImageElement()
    if (!viewport || !image || !image.complete) return

    const distance = Math.max(0, image.offsetHeight - viewport.clientHeight)
    image.style.transitionDuration = "0s"
    image.style.transform = "translate3d(0, 0, 0)"

    if (!distance) return

    requestAnimationFrame(() => requestAnimationFrame(() => {
      if (!isPreviewOpen.value) return

      const duration = Math.min(20, Math.max(5, distance / 100))
      image.style.transitionDuration = `${duration}s`
      image.style.transitionTimingFunction = "linear"
      image.style.transform = `translate3d(0, -${distance}px, 0)`
    }))
  }, 350)
}

const resetModalPreview = () => {
  clearTimeout(modalScrollTimer)

  const image = getModalImageElement()
  if (image) {
    image.style.transitionDuration = "0s"
    image.style.transform = "translate3d(0, 0, 0)"
  }

  selectedProduct.value = null
}

onBeforeUnmount(() => clearTimeout(modalScrollTimer))
</script>

import { computed, markRaw, unref, type MaybeRef } from 'vue'
import { getDetailComponents } from '~/utils/getDetailComponents'

export function useMappedDetailPage(page: MaybeRef<string>) {
  const components = getDetailComponents()

  const detailComponent = computed(() => {
    const key = unref(page)

    if (!key) return null

    const normalized = String(key)
      .toLowerCase()
      .replace(/s$/, '')

    const comp = components[normalized]

    return comp ? markRaw(comp) : null
  })

  return {
    detailComponent,
  }
}
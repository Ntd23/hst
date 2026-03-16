export function getDetailComponents() {
  const modules = import.meta.glob('~/components/pages/details/*.vue', {
    eager: true,
  })

  const components: Record<string, any> = {}

  for (const path in modules) {
    const name = path
      .split('/')
      .pop()
      ?.replace('.vue', '')

    if (!name) continue

    components[name.toLowerCase()] = (modules[path] as any).default
  }

  return components
}
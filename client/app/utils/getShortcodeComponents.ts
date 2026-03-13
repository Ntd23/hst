export function getShortcodeComponents() {

    const modules = import.meta.glob('@/components/shortcode/*.vue', {
        eager: true
    })

    const components: Record<string, any> = {}

    for (const path in modules) {
        const name = path
            .split('/')
            .pop()
            ?.replace('.vue', '')
        if (!name) continue
        const componentName =
            'Shortcode' +
            name
                .split('-')
                .map(i => i.charAt(0).toUpperCase() + i.slice(1))
                .join('')
        components[componentName] = (modules[path] as any).default
    }

    return components
}
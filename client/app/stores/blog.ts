import { defineStore } from 'pinia'

export const useBlogStore = defineStore('blog', {
    state: () => ({
        posts: [] as any[],
        loading: false
    }),

    actions: {
        async fetchPosts() {
            this.loading = true

            try {
                const data = await $fetch('/api/pages/blog')
                this.posts = data as any[]

            } catch (error) {
                console.error(error)
            } finally {
                this.loading = false
            }
        }
    }
})
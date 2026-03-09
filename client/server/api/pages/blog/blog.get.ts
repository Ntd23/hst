import { apiFetch } from '~~/server/utils/apiFetch'
import type { H3Event } from 'h3'
export const blogApi = {
    getPost: (event: H3Event) => {
        return apiFetch(event, '/blog')
    },
    getPostStore: (event: H3Event) => {
        return apiFetch(event, '/blog/store')
    },
}
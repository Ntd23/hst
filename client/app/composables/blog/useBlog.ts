export function useBlogs() {

  const fetchBlogs = async () => {
    return await $fetch('/api/pages/blog')
  }
  return {
    fetchBlogs
  }

}
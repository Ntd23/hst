export default defineNuxtRouteMiddleware((to, from) => {
  // Kiểm tra authentication
  const isAuthenticated = false // Thay bằng logic thật

  if (!isAuthenticated && to.path !== '/login') {
    return navigateTo('/login')
  }
})

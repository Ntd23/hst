export default defineNuxtRouteMiddleware((to, from) => {
  console.log('Navigating to:', to.path)
})

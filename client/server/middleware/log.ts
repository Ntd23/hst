
export default defineEventHandler((event) => {
  console.log(`[${new Date().toISOString()}] ${event.node.req.method} ${event.node.req.url}`)
})

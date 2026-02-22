export default defineEventHandler((event) => {
  const existing = getHeader(event, 'x-request-id')
  const id = existing || crypto.randomUUID()
  setHeader(event, 'x-request-id', id)
})

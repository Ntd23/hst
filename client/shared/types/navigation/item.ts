export type NavTarget = '_self' | '_blank' | string

export type NavReferenceType =
  | 'Botble\\Page\\Models\\Page'
  | 'Botble\\Blog\\Models\\Post'
  | string
  | null

export type NavItem = {
  id: number
  title: string
  url: string
  target: NavTarget
  class: string
  icon: string
  position: number
  reference_id: number | null
  reference_type: NavReferenceType
  children: NavItem[]
}
// Định nghĩa 1 item menu đúng theo JSON Botble trả về: id, title, url, target, children…

// Mục tiêu: mọi nơi (server, client, component) dùng cùng một type, không phải đoán field.

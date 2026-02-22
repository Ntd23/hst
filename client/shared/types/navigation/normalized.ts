import type { NavItem } from './item'

export type NavItemNormalized = NavItem & {
  // link dùng trực tiếp trong UI
  path: string          // ví dụ: "/", "/blog", "/gioi-thieu-ve-cong-ty"
  external: boolean     // true nếu khác domain
  hasChildren: boolean  // tiện cho template
}

export type NavigationResponseNormalized = {
  location: string
  menu_id: number
  data: NavItemNormalized[]
}

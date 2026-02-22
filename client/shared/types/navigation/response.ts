// shared/types/navigation/response.ts
import type { NavItem } from './item'

export type NavigationResponse = {
  location: string
  menu_id: number
  data: NavItem[]
}
// Định nghĩa response đầy đủ của endpoint navigation
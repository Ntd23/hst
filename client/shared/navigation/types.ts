export type MenuItem = {
  id?: number | string;
  title?: string;
  label?: string;
  url?: string;
  to?: string;
  reference_id?: number | string | null;
  reference_type?: string | null;
  children?: MenuItem[];
};

export type status = 'idle' | 'pending' | 'success' | 'error';

export interface UserInfo {
  id: number;
  name: string;
  email: string;
  created_at: string;
  updated_at: string;
}


export interface Payment {
  customer_id: number;
  customer_name: string;
  customer_email: string;
  amount: number;
  currency: string;
  amount_usd: number | null;
  reference_no: string;
  date_time: string; // ISO datetime string
  processed: boolean;
}

export interface PaginationMeta {
  current_page: number;
  from: number;
  last_page: number;
  path: string;
  per_page: number;
  to: number;
  total: number;
  links?: PaginationLink[];
}

interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

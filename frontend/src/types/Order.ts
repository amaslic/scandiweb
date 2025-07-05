export interface Order {
  success: boolean;
  message: string;
  total: number;
  items: string[];
  notFound?: string[];
}

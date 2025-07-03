export interface OrderResponse {
  success: boolean;
  message: string;
  total: number;
  items: string[];
  notFound?: string[];
}

export interface Order {
  success: boolean;
  message: string;
  total: number;
  items: string[]; // lista SKU-ova
  notFound?: string[]; // ako frontend koristi ovo iz backend-a
}

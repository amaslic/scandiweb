export interface ProductInput {
  sku: string;
  quantity: number;
  selectedAttributes?: Record<string, string>; // { Size: "M", Color: "Black" }
}

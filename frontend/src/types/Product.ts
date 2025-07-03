import type { AttributeSet } from "./Attribute";
import type { Price } from "./Price";


export interface Product {
  id: string; // actually SKU
  name: string;
  price: number;
  prices: Price[];
  brand?: string;
  inStock?: boolean;
  description?: string;
  category: string;
  gallery: string[];
  attributes: AttributeSet[];
}

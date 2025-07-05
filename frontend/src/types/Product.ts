import type { AttributeSet } from "./Attribute";
import type { Category } from "./Category";
import type { Price } from "./Price";


export interface Product {
  id: string;
  sku: string;
  name: string;
  prices: Price[];
  brand?: string;
  inStock?: boolean;
  description?: string;
  category: Category;
  gallery: string[];
  attributes: AttributeSet[];
}

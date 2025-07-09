import type { AttributeSet } from "./Attribute";


export interface ItemCart {
  id: string,
  sku: string,
  price: number,
  quantity: number,
  itemTotal: number,
  image: string,
  attributes: AttributeSet[],
  name: string
}

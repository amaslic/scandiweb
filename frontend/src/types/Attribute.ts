export interface AttributeItem {
  id: string;
  value: string;
  displayValue: string;
}

export interface AttributeSet {
  id: string;
  name: string;
  type: string;
  items: AttributeItem[];
}

import type { Currency } from "./Currency";

export interface Price {
  amount: number;
  currency: Currency;
}
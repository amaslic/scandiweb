import { gql } from "@apollo/client";

export interface PlaceOrderResponse {
  placeOrder: {
    success: boolean;
    message: string;
    orderId: number | null;
  };
}

export interface PlaceOrderVariables {
  items: {
    sku: string;
    qty: number;
    attributes: {
      id: number;
      value: string;
      display_value?: string;
    }[];
  }[];
}

export const PLACE_ORDER_MUTATION = gql`
  mutation PlaceOrder($items: [OrderItemInput!]!) {
    placeOrder(items: $items) {
      success
      message
      orderId
    }
  }
`;
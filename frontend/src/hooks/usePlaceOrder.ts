import { useMutation } from "@apollo/client";
import { useCart } from "react-use-cart";
import {
  PLACE_ORDER_MUTATION,
  type PlaceOrderResponse,
  type PlaceOrderVariables,
} from "../queries/placeOrderMutation";

export const usePlaceOrder = () => {
  const { items, emptyCart } = useCart();

  const [placeOrderMutation, { data, error, loading }] = useMutation<
    PlaceOrderResponse,
    PlaceOrderVariables
  >(PLACE_ORDER_MUTATION);

  const placeOrder = async () => {
    const formattedItems = items.map((item) => ({
      sku: item.sku,
      qty: item.quantity ?? 1,
      attributes: item.attributes.map((attr: any) => ({
        id: attr.id,
        value: attr.selectedItem.value,
        display_value: attr.selectedItem.displayValue,
      })),
    }));

    const result = await placeOrderMutation({
      variables: { items: formattedItems },
    });

    if (result.data?.placeOrder?.success) {
      emptyCart();
    }

    return result;
  };

  return {
    placeOrder,
    data,
    loading,
    error,
  };
};

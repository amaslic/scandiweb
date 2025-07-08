import { usePlaceOrder } from "../../hooks/usePlaceOrder";
import ToastHandler from "../ToastHandler";

interface Props {
  total: number;
  isEmpty: boolean;
}

function CartFooter({ total, isEmpty }: Props) {
  const { placeOrder, loading } = usePlaceOrder();

  const handlePlaceOrder = async () => {
    try {
      const response = await placeOrder();

      if (response?.data?.placeOrder?.success) {
        ToastHandler.successOrder(response.data.placeOrder.message);
      } else {
        ToastHandler.errorOrder();
      }
    } catch (err) {
      ToastHandler.errorOrder();
    }
  };

  return (
    <div className="cart-footer">
      <div className="cart-total" data-testid="cart-total">
        <span>Total</span>
        <span className="cart-total-value">${total.toFixed(2)}</span>
      </div>

      <button
        className={`cart-place-order-btn ${
          isEmpty ? "opacity-50 cursor-not-allowed" : ""
        }`}
        disabled={isEmpty || loading}
        onClick={handlePlaceOrder}
      >
        {loading ? "Ordering..." : "Place Order"}
      </button>
    </div>
  );
}

export default CartFooter;

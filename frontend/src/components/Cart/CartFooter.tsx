interface Props {
  total: number;
  isEmpty: boolean;
}

function CartFooter({ total, isEmpty }: Props) {
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
        disabled={isEmpty}
      >
        Place Order
      </button>
    </div>
  );
}

export default CartFooter;

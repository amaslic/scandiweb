import { useCart } from "react-use-cart";
import CartHeader from "./CartHeader";
import CartItem from "./CartItem";
import CartFooter from "./CartFooter";
import type { ItemCart } from "../../types/ItemCart";
interface Props {
  onClose: () => void;
}

function Cart({ onClose }: Props) {
  const { isEmpty, items, updateItemQuantity, cartTotal } = useCart();

  return (
    <div className="cart-container">
      <CartHeader
        itemCount={items.reduce(
          (total, item) => total + (item.quantity ?? 1),
          0
        )}
        onClose={onClose}
      />

      <div className="cart-items-container">
        {isEmpty ? (
          <p>Your cart is empty.</p>
        ) : (
          items.map((item) => (
            <CartItem
              key={item.id}
              item={item as ItemCart}
              onIncrease={() => updateItemQuantity(item.id, item.quantity! + 1)}
              onDecrease={() => updateItemQuantity(item.id, item.quantity! - 1)}
            />
          ))
        )}
      </div>

      {
        <CartFooter
          total={cartTotal}
          isEmpty={isEmpty}
        />
      }
    </div>
  );
}

export default Cart;

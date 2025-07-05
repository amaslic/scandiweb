import { X } from "lucide-react";

interface Props {
  itemCount: number;
  onClose: () => void;
}

function CartHeader({ itemCount, onClose }: Props) {
  return (
    <div className="cart-header">
      <h2 className="cart-header-title">
        My Bag.
        <span className="cart-header-count">
          {itemCount} {itemCount === 1 ? "item" : "items"}
        </span>
      </h2>
      <button onClick={onClose} aria-label="Close cart">
        <X />
      </button>
    </div>
  );
}

export default CartHeader;

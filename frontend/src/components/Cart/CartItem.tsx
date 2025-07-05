import CartAttributes from "./CartAttributes";

import ToastHandler from "../ToastHandler";


interface Props {
  item: any;
  onIncrease: () => void;
  onDecrease: () => void;
}

function CartItem({ item, onIncrease, onDecrease }: Props) {

  const onClickIncrease = () => {
    onIncrease();
    ToastHandler.successProductAdd(item.name);
  };

  const onClickDecrease= () => {
    onDecrease();
    ToastHandler.successProductRemove(item.name);
  };

  return (
    <div className="cart-item">
      <div className="cart-item-details">
        <div className="cart-item-header">
          <p className="cart-item-name">{item.name}</p>
        </div>
        <p className="cart-price">${item.price.toFixed(2)}</p>

        {item.attributes && <CartAttributes attributes={item.attributes} />}

        <div className="cart-quantity-controls">
          <button
            className="cart-quantity-btn"
            onClick={onClickDecrease}
            data-testid="cart-item-amount-decrease"
          >
            -
          </button>
          <span>{item.quantity}</span>
          <button
            className="cart-quantity-btn"
            onClick={onClickIncrease}
            data-testid="cart-item-amount-increase"
          >
            +
          </button>
        </div>
      </div>

      <img src={item.image} alt={item.name} className="cart-item-image" />
    </div>
  );
}

export default CartItem;

import { Link } from "react-router-dom";
import { useCart } from "react-use-cart";
import type { Product } from "../../types/Product";
import ToastHandler from "../ToastHandler";

interface Props extends Product {
  onAddToCart: () => void;
}

function ProductListItem({
  sku,
  name,
  inStock,
  gallery,
  prices,
  attributes,
  onAddToCart,
}: Props) {
  const { addItem } = useCart();
  const [price] = prices;

  const handleAddToCart = (e: React.MouseEvent) => {
    e.preventDefault();

    const defaultAttributes =
      attributes?.map((attr) => {
        const first = attr.items[0]!;
        return {
          id: attr.id,
          name: attr.name,
          selectedItem: {
            id: first.value,
            value: first.value,
            displayValue: first.displayValue,
          },
          items: attr.items.map((item) => ({
            id: item.value,
            value: item.value,
            displayValue: item.displayValue,
          })),
        };
      }) || [];

    addItem({
      id: `${sku}-${defaultAttributes
        .map((a) => a.selectedItem.value)
        .join("-")}`,
      sku: sku,
      name,
      price: price.amount ?? 0,
      attributes: defaultAttributes,
      image: gallery[0] ?? "",
    });

    ToastHandler.successProductAdd(name);
    onAddToCart();
  };
  const productSlug = name.toLowerCase().replace(/\s+/g, "-");

  return (
    <Link
      to={`/product/${sku}`}
      className="group product-list-item"
      data-testid={`product-${productSlug}`}
    >
      <div
        className={`product-image-wrapper ${
          !inStock ? "opacity-50 grayscale" : ""
        }`}
      >
        {gallery[0] && (
          <img
            src={gallery[0]}
            alt={name}
            className="product-image"
            loading="lazy"
          />
        )}

        {!inStock && <div className="product-out-of-stock">OUT OF STOCK</div>}

        {inStock && (
          <button
            onClick={handleAddToCart}
            className="product-add-button"
            aria-label="Add to Cart"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              height="20"
              width="20"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.3 5.4A1 1 0 007 20h10a1 1 0 001-1l-1.3-5.4M9 20a2 2 0 104 0 2 2 0 10-4 0z"
              />
            </svg>
          </button>
        )}
      </div>

      <div className="product-info">
        <h2 className="product-name">{name}</h2>
        <p className="product-price">
          {price.currency.symbol}
          {price.amount.toFixed(2)}
        </p>
      </div>
    </Link>
  );
}

export default ProductListItem;

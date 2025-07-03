import { Link } from "react-router-dom";
import { useCart } from "react-use-cart";
import { useMemo } from "react";
import type { Product } from "../../types/Product";

interface Props extends Product {
  onAddToCart: () => void;
}

function ProductListItem({
  id,
  name,
  inStock,
  gallery,
  prices,
  attributes,
  onAddToCart,
}: Props) {
  const { addItem } = useCart();

  const defaultAttributes = useMemo(() => {
    return (
      attributes?.map((attr) => {
        const firstItem = attr.items[0];
        return {
          id: attr.id,
          name: attr.name,
          selectedItem: {
            id: firstItem?.id,
            value: firstItem?.value,
            displayValue: firstItem?.displayValue,
          },
          items: attr.items.map((item) => ({
            id: item.id,
            value: item.value,
            displayValue: item.displayValue,
          })),
        };
      }) || []
    );
  }, [attributes]);

  const handleAddToCart = (e: React.MouseEvent) => {
    e.preventDefault();

    addItem({
      id: `${id}-${defaultAttributes
        .map((a) => a.selectedItem?.value)
        .join("-")}`,
      name,
      price: prices[0].amount,
      attributes: defaultAttributes,
      image: gallery[0] ?? "",
    });

    onAddToCart();
  };

  const productSlug = name.toLowerCase().replace(/\s+/g, "-");

  return (
    <Link
      to={`/product/${id}`}
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
          {prices[0]?.currency.symbol}
          {prices[0]?.amount.toFixed(2)}
        </p>
      </div>
    </Link>
  );
}

export default ProductListItem;

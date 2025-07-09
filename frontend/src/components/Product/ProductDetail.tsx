import { useState } from "react";
import { useParams } from "react-router-dom";
import { useQuery } from "@apollo/client";
import { useCart } from "react-use-cart";
import parse from "html-react-parser";
import type { Product } from "../../types/Product";
import { GET_PRODUCT_BY_SKU } from "../../queries/getProducts";
import ProductGallery from "./ProductGallery";
import ToastHandler from "../ToastHandler";
import ProductNotFound from "../NotFound/ProductNotFound";
import Loader from "../Loader/Loader";

function ProductDetail({ onAddToCart }: { onAddToCart: () => void }) {
  const { sku } = useParams<{ sku: string }>();
  const { addItem } = useCart();

  const { data, loading, error } = useQuery<{ product: Product }>(
    GET_PRODUCT_BY_SKU,
    { variables: { sku: sku } }
  );

  const [selectedAttributes, setSelectedAttributes] = useState<{
    [attrId: string]: string;
  }>({});

  if (loading) return <Loader />;
  if (error || !data?.product) return <ProductNotFound />;

  const product = data.product;

  const [price] = data.product.prices;

  const handleSelectAttribute = (attrId: string, itemId: string) => {
    setSelectedAttributes((prev) => ({
      ...prev,
      [attrId]: itemId,
    }));
  };

  const isAllAttributesSelected =
    product.attributes.length === Object.keys(selectedAttributes).length;

  const handleAddToCart = () => {
    const suffix = product.attributes
      .map((attr) => selectedAttributes[attr.id] || attr.items[0].value)
      .join("-");

    addItem({
      id: `${product.sku}-${suffix}`,
      sku: sku,
      name: product.name,
      price: price?.amount ?? 0,
      attributes: product.attributes.map((attr) => {
        const selectedItem = attr.items.find(
          (item) => item.value === selectedAttributes[attr.id]
        );

        return {
          id: attr.id,
          name: attr.name,
          selectedItem: {
            id: selectedItem?.value,
            value: selectedItem?.value,
            displayValue: selectedItem?.displayValue,
          },
          items: attr.items.map((item) => ({
            id: item.value,
            value: item.value,
            displayValue: item.displayValue,
          })),
        };
      }),
      image: product.gallery[0] ?? "",
    });
    ToastHandler.successProductAdd(product.name);
    onAddToCart();
  };

  return (
    <div className="product-container">
      <div className="product-grid">
        <ProductGallery images={product.gallery} />

        <div className="product-section">
          <h1 className="product-title">{product.name}</h1>

          {product.attributes.map((attr) => {
            const kebabAttr = attr.name.toLowerCase().replace(/\s+/g, "-");
           
            const isColorAttr = attr.name.toLowerCase() === "color";

            return (
              <div key={attr.id} data-testid={`product-attribute-${kebabAttr}`}>
                <h2 className="product-attribute-label">{attr.name}:</h2>
                <div className="product-attribute-group">
                  {attr.items.map((item) => {
                    const isSelected =
                      selectedAttributes[attr.id] === item.value;
                     const kebabValue = item.value
                    .toLowerCase()
                    .replace(/\s+/g, "-");
                    return (
                      <button
                        data-testid={`product-attribute-${kebabAttr}-${kebabValue}`}
                        key={item.value}
                        onClick={() =>
                          handleSelectAttribute(attr.id, item.value)
                        }
                        title={item.value}
                        className={`${
                          isColorAttr
                            ? "attribute-color-base"
                            : "attribute-button-base"
                        } ${
                          isSelected
                            ? isColorAttr
                              ? "attribute-color-selected"
                              : "attribute-button-selected"
                            : isColorAttr
                            ? "attribute-color-unselected"
                            : "attribute-button-unselected"
                        }`}
                        style={
                          isColorAttr ? { backgroundColor: item.value } : {}
                        }
                      >
                        {!isColorAttr && item.value}
                      </button>
                    );
                  })}
                </div>
              </div>
            );
          })}

          <div>
            <h2 className="product-price-label">Price:</h2>
            <p className="product-price-value">
              {price.currency.symbol} {price.amount.toFixed(2)}
            </p>
          </div>

          {product.inStock && (
            <button
              onClick={handleAddToCart}
              disabled={!isAllAttributesSelected}
              className={`add-to-cart-button ${
                isAllAttributesSelected ? "button-enabled" : "button-disabled"
              }`}
              data-testid="add-to-cart"
            >
              Add to cart
            </button>
          )}

          <div
            className="product-description"
            data-testid="product-description"
          >
            {parse(product.description || "")}
          </div>
        </div>
      </div>
    </div>
  );
}

export default ProductDetail;

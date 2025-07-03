import { useState } from "react";
import { useParams } from "react-router-dom";
import { useQuery } from "@apollo/client";
import { useCart } from "react-use-cart";
import parse from "html-react-parser";
import type { Product } from "../../types/Product";
import { GET_PRODUCT_BY_SKU } from "../../queries/getProducts";
import ProductGallery from "./ProductGallery";


function ProductDetail({ onAddToCart }: { onAddToCart: () => void }) {
  const { id } = useParams<{ id: string }>();
  const { addItem } = useCart();

  const { data, loading, error } = useQuery<{ product: Product }>(
    GET_PRODUCT_BY_SKU,
    { variables: { sku: id } }
  );

  const [selectedAttributes, setSelectedAttributes] = useState<{
    [attrId: string]: string;
  }>({});

  if (loading) return <p>Loading...</p>;
  if (error || !data?.product) return <p>Product not found.</p>;

  const product = data.product;

  const handleSelectAttribute = (attrId: string, itemId: string) => {
    setSelectedAttributes((prev) => ({
      ...prev,
      [attrId]: itemId,
    }));
  };

  const isAllAttributesSelected =
    product.attributes.length === Object.keys(selectedAttributes).length;

  const handleAddToCart = () => {
    addItem({
      id: `${product.id}-${Object.values(selectedAttributes).join("-")}`,
      name: product.name,
      price: product.price,
      attributes: product.attributes.map((attr) => {
        const selectedItem = attr.items.find(
          (item) => item.id === selectedAttributes[attr.id]
        );
        return {
          id: attr.id,
          name: attr.name,
          selectedItem: {
            id: selectedItem?.id,
            value: selectedItem?.value,
            displayValue: selectedItem?.displayValue,
          },
          items: attr.items.map((item) => ({
            id: item.id,
            value: item.value,
            displayValue: item.displayValue,
          })),
        };
      }),
      image: product.gallery[0] ?? "",
    });

    onAddToCart();
  };

  return (
    <div className="product-container">
      <div className="product-grid">
        <div data-testid="product-gallery">
          <ProductGallery images={product.gallery} />
        </div>

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
                    const isSelected = selectedAttributes[attr.id] === item.id;

                    return (
                      <button
                        key={item.id}
                        onClick={() => handleSelectAttribute(attr.id, item.id)}
                        title={item.displayValue}
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
                        {!isColorAttr && item.displayValue}
                      </button>
                    );
                  })}
                </div>
              </div>
            );
          })}

          <div>
            <h2 className="product-price-label">Price:</h2>
            <p className="product-price-value">${product.price.toFixed(2)}</p>
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


import { useQuery } from "@apollo/client";
import { GET_PRODUCTS } from "../queries/getProducts";
import { GET_CATEGORIES } from "../queries/getCategories";
import { useParams } from "react-router-dom";
import ProductListItem from "./Product/ProductListItem";

import type { Product } from "../types/Product";
import type { Category } from "../types/Category";

interface Props {
  onAddToCart: () => void;
}

function ProductList({ onAddToCart }: Props) {
  const { name } = useParams<{ name: string }>();
  const categoryName = name || "all";

  const {
    data: categoryData,
    loading: loadingCategories,
    error: categoryError,
  } = useQuery<{ categories: Category[] }>(GET_CATEGORIES);

  const selectedCategory = categoryData?.categories.find(
    (c) => c.name === categoryName
  );
  const categoryId = selectedCategory?.id ? parseInt(selectedCategory.id) : 1;

  const {
    data: productData,
    loading: loadingProducts,
    error: productError,
  } = useQuery<{ products: Product[] }>(GET_PRODUCTS, {
    variables: { categoryId },
    skip: loadingCategories,
  });

  if (loadingCategories || loadingProducts) return <p>Loading...</p>;
  if (categoryError || productError) return <p>Error loading products.</p>;

  return (
    <div className="product-list-grid">
      {productData?.products.map((product) => (
        <div key={product.sku}>
          <ProductListItem {...product} onAddToCart={onAddToCart} />
        </div>
      ))}
    </div>
  );
}

export default ProductList;

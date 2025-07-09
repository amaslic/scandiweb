import { useQuery } from "@apollo/client";
import { GET_PRODUCTS } from "../queries/getProducts";
import { GET_CATEGORIES } from "../queries/getCategories";
import { Navigate, useParams } from "react-router-dom";
import ProductListItem from "./Product/ProductListItem";

import type { Product } from "../types/Product";
import type { Category } from "../types/Category";
import Loader from "./Loader/Loader";

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
  const categoryId = selectedCategory?.id ? selectedCategory.id : 1;

  const {
    data: productData,
    loading: loadingProducts,
    error: productError,
  } = useQuery<{ products: Product[] }>(GET_PRODUCTS, {
    variables: { categoryId },
    skip: loadingCategories,
  });

  if (loadingCategories || loadingProducts) return <Loader />;
  if (categoryError || productError) return <p>Error loading products.</p>;

  const validCategoryNames = categoryData?.categories.map((c) => c.name) || [];

  const isValidCategory = !name || validCategoryNames.includes(name);

  if (!isValidCategory) {
    return <Navigate to="/404" replace />;
  }

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

import { gql } from "@apollo/client";

export const GET_PRODUCTS = gql`
  query GetProducts($categoryId: Int!) {
    products(categoryId: $categoryId) {
      id
      name
      inStock
      attributes {
        id
        name
        items {
          id
          value
          displayValue
        }
      }
      prices {
        amount
        currency {
          label
          symbol
        }
      }
      gallery
    }
  }
`;

export const GET_PRODUCT_BY_SKU = gql`
  query GetProduct($sku: String!) {
    product(sku: $sku) {
      id
      name
      price
      description
      inStock
      gallery
      brand
      attributes {
        id
        name
        items {
          id
          value
          displayValue
        }
      }
    }
  }
`;

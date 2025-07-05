import { gql } from "@apollo/client";

export const GET_PRODUCTS = gql`
  query GetProducts($categoryId: Int!) {
    products(categoryId: $categoryId) {
      id
      sku
      name
      inStock
      brand
      gallery
      description
      category {
        id
        name
      }
      prices {
        amount
        currency {
          label
          symbol
        }
      }
      attributes {
        id
        name
        type
        items {
          value
          displayValue
        }
      }
    }
  }
`;

export const GET_PRODUCT_BY_SKU = gql`
  query GetProduct($sku: String!) {
    product(sku: $sku) {
      id
      sku
      name
      description
      inStock
      gallery
      brand
      category {
        id
        name
      }
      prices {
        amount
        currency {
          label
          symbol
        }
      }
      attributes {
        id
        name
        type
        items {
          value
          displayValue
        }
      }
    }
  }
`;

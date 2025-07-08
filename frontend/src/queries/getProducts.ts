import { gql } from "@apollo/client";

const PRODUCT_FIELDS = gql`
  fragment ProductFields on Product {
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
`;

export const GET_PRODUCTS = gql`
  ${PRODUCT_FIELDS}
  query GetProducts($categoryId: Int!) {
    products(categoryId: $categoryId) {
      ...ProductFields
    }
  }
`;

export const GET_PRODUCT_BY_SKU = gql`
  ${PRODUCT_FIELDS}
  query GetProduct($sku: String!) {
    product(sku: $sku) {
      ...ProductFields
    }
  }
`;

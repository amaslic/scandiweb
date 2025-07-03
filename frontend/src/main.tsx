import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import "./index.css";
import App from "./App.tsx";
import { ApolloProvider } from "@apollo/client/react/context/ApolloProvider";
import client from "./appoloClient.ts";
import { CartProvider } from "react-use-cart";
import { BrowserRouter } from "react-router-dom";

createRoot(document.getElementById("root")!).render(
  <StrictMode>
    <ApolloProvider client={client}>
      <BrowserRouter>
        <CartProvider>
          <App />
        </CartProvider>
      </BrowserRouter>
    </ApolloProvider>
  </StrictMode>
);

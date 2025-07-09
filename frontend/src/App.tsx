import { Routes, Route, Navigate } from "react-router-dom";
import ProductList from "./components/ProductsList";
import Cart from "./components/Cart/Cart";
import ProductDetail from "./components/Product/ProductDetail";
import { useState } from "react";
import Header from "./components/Header/Header";
import { ToastRoot } from "./components/ToastHandler";
import NotFound from "./components/NotFound/404";

function App() {
  const [cartOpen, setCartOpen] = useState(false);

  return (
    <div className="app-wrapper">
      <Header onCartClick={() => setCartOpen(true)} />

      {/* Cart */}
      {cartOpen && <Cart onClose={() => setCartOpen(false)} />}

      {/* Main content */}
      <main
        className={`app-main ${
          cartOpen ? "app-main-overlay pointer-events-none" : ""
        }`}
      >
        <Routes>
          <Route
            path="/"
            element={<ProductList onAddToCart={() => setCartOpen(true)} />}
          />
          <Route
            path="/:name"
            element={<ProductList onAddToCart={() => setCartOpen(true)} />}
          />
          <Route
            path="/product/:sku"
            element={<ProductDetail onAddToCart={() => setCartOpen(true)} />}
          />
          <Route path="/404" element={<NotFound />} />
          <Route path="*" element={<Navigate to="/404" replace />} />
        </Routes>
      </main>

      <ToastRoot />
    </div>
  );
}

export default App;

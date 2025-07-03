import { Routes, Route } from "react-router-dom";
import ProductList from "./components/ProductsList";
import Cart from "./components/Cart/Cart";
import ProductDetail from "./components/Product/ProductDetail";
import { useState } from "react";
import Header from "./components/Header/Header";

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
          <Route path="/" element={<ProductList onAddToCart={() => setCartOpen(true)} />} />
          <Route path="/category/:name" element={<ProductList onAddToCart={() => setCartOpen(true)} />} />
          <Route path="/product/:id" element={<ProductDetail onAddToCart={() => setCartOpen(true)} />} />
        </Routes>
      </main>
    </div>
  );
}

export default App;

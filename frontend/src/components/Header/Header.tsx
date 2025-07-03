import { useEffect, useState } from "react";
import { ShoppingCart, Menu } from "lucide-react";
import { useCart } from "react-use-cart";
import { gql, useQuery } from "@apollo/client";
import { useNavigate } from "react-router-dom";
import DesktopMenu from "./DesktopMenu";
import MobileMenu from "./MobileMenu";
import type { Category } from "../../types/Category";

const GET_CATEGORIES = gql`
  query {
    categories {
      id
      name
    }
  }
`;

interface Props {
  onCartClick: () => void;
}

function Header({ onCartClick }: Props) {
  const [activeCategory, setActiveCategory] = useState("");
  const [menuOpen, setMenuOpen] = useState(false);
  const { items } = useCart();
  const navigate = useNavigate();
  const { data, loading } = useQuery(GET_CATEGORIES);
  const categories: Category[] = data?.categories || [];
  const totalItems = items.reduce((sum, item) => sum + (item.quantity ?? 1), 0);

  useEffect(() => {
    if (!loading && categories.length > 0 && !activeCategory) {
      setActiveCategory(categories[0].name);
    }
  }, [loading, categories, activeCategory]);

  const onMenuItemClick = (cat: Category) => {
    setActiveCategory(cat.name);
    navigate(cat.name === "All" ? "/" : `/category/${cat.name}`);
  };

  const onClickLogo = () => {
    setActiveCategory(categories[0].name);
    navigate("/");
  };

  return (
    <header className="header-container">
      <DesktopMenu
        categories={categories}
        activeCategory={activeCategory}
        onMenuItemClick={onMenuItemClick}
      />

      <button className="menu-button" onClick={() => setMenuOpen(!menuOpen)}>
        <Menu />
      </button>

      <div className="logo-container">
        <div className="logo-badge" onClick={onClickLogo}>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            height="18"
            width="18"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth="2"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.3 5.4A1 1 0 007 20h10a1 1 0 001-1l-1.3-5.4M9 20a2 2 0 104 0 2 2 0 10-4 0z"
            />
          </svg>
        </div>
      </div>

      <button
        onClick={onCartClick}
        className="cart-button"
        data-testid="cart-btn"
      >
        <ShoppingCart className="w-6 h-6" />
        {totalItems > 0 && <span className="cart-badge">{totalItems}</span>}
      </button>

      {menuOpen && (
        <MobileMenu
          categories={categories}
          activeCategory={activeCategory}
          setActiveCategory={setActiveCategory}
          closeMenu={() => setMenuOpen(false)}
        />
      )}
    </header>
  );
}

export default Header;

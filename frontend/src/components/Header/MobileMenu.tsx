import type { Category } from "../../types/Category";

interface Props {
  categories: Category[];
  activeCategory: string;
  setActiveCategory: (cat: string) => void;
  closeMenu: () => void;
}

export default function MobileMenu({
  categories,
  activeCategory,
  setActiveCategory,
  closeMenu,
}: Props) {
  return (
    <div className="mobile-menu">
      {categories.map((cat) => (
        <button
          key={cat.id}
          onClick={() => {
            setActiveCategory(cat.name);
            closeMenu();
          }}
          className={`mobile-button ${
            activeCategory === cat.name
              ? "mobile-button-active"
              : "mobile-button-inactive"
          }`}
          data-testid={
            activeCategory === cat.name
              ? "active-category-link"
              : "category-link"
          }
        >
          {cat.name}
        </button>
      ))}
    </div>
  );
}

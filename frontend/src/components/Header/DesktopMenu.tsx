import type { Category } from "../../types/Category";

interface Props {
  categories: Category[];
  activeCategory: string;
  onMenuItemClick: (cat: Category) => void;
}

export default function DesktopMenu({
  categories,
  activeCategory,
  onMenuItemClick,
}: Props) {
  return (
    <nav className="nav-desktop">
      {categories.map((cat) => (
        <button
          key={cat.id}
          onClick={() => onMenuItemClick(cat)}
          className={`nav-button ${
            activeCategory === cat.name
              ? "nav-button-active"
              : "nav-button-inactive"
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
    </nav>
  );
}

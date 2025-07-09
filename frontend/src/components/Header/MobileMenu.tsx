import { Link } from "react-router-dom";
import type { Category } from "../../types/Category";

interface Props {
  categories: Category[];
  activeCategory: string;
  closeMenu: () => void;
}

export default function MobileMenu({
  categories,
  activeCategory,
  closeMenu,
}: Props) {
  return (
    <div className="mobile-menu">
      {categories.map((cat) => {
        const slug = cat.name.toLowerCase();
        const link = slug === "all" ? "/" : `/${slug}`;
        const isActive = activeCategory === slug;

        return (
          <Link
            key={cat.id}
            to={link}
            onClick={closeMenu}
            className={`nav-button ${isActive ? "nav-button-active" : "nav-button-inactive"}`}
            data-testid={isActive ? "active-category-link" : "category-link"}
          >
            {cat.name}
          </Link>
        );
      })}
    </div>
  );
}

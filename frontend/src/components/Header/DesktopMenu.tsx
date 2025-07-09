import { Link } from "react-router-dom";
import type { Category } from "../../types/Category";

interface Props {
  categories: Category[];
  activeCategory: string;
}

export default function DesktopMenu({ categories, activeCategory }: Props) {
  return (
    <nav className="nav-desktop">
      {categories.map((cat) => {
        const slug = cat.name.toLowerCase();
        const link = `/${slug}`;
        const isActive = activeCategory === slug;

        return (
          <Link
            key={cat.id}
            to={link}
            className={`nav-button ${
              isActive ? "nav-button-active" : "nav-button-inactive"
            }`}
            data-testid={isActive ? "active-category-link" : "category-link"}
          >
            {cat.name}
          </Link>
        );
      })}
    </nav>
  );
}

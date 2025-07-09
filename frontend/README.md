# Frontend — Scandiweb Fullstack Test

## Stack Used

- React
- Vite – Fast dev server and optimized build
- Tailwind CSS – Utility-first CSS framework
- Apollo Client – Used for GraphQL queries and caching

---

## Folder Structure

```
frontend/
│
├── public/                  # Static assets
├── src/
│   ├── assets/              # Static images and styles
│   │   └── styles/          # Tailwind-based modular CSS files
│   ├── components/          # React components (Cart, Header, ProductList, etc.)
│   ├── hooks/               # Custom React hooks
│   ├── queries/             # GraphQL queries
│   ├── types/               # TypeScript interfaces/types
│   ├── App.tsx              # App entry with routing setup
│   ├── appoloClient.ts      # Apollo GraphQL client setup
│   ├── main.tsx             # React DOM root
│   └── index.css            # Tailwind & global CSS imports
│
├── .env.*                   # Vite environment configs
├── vite.config.ts           # Vite config
├── vercel.json              # Rewrites for SPA fallback (important!)
├── README.md                # You are here
```

---

## Tailwind CSS Setup

Tailwind CSS was used with custom `@apply` utilities extracted into reusable `.css` files inside `src/assets/styles`.

To use `@apply`, follow [Tailwind’s official guide on `@apply`](https://tailwindcss.com/docs/reusing-styles#extracting-classes-with-apply).

Example:
```css
/* styles/button.css */
.btn-primary {
  @apply bg-blue-600 text-white px-4 py-2 rounded;
}
```

Then imported in `index.css`:
```css
@import './assets/styles/button.css';
```

---

## Scripts

| Script | Description |
|--------|-------------|
| `npm install` | Install dependencies |
| `npm run dev` | Run Vite dev server |
| `npm run build` | Build for production |
| `npm run preview` | Preview production build locally |

---

## Deployment

The app is deployed using Vercel with SPA rewrites for all routes. Routing is handled entirely by React Router.

---

## Useful Links

- [Tailwind CSS Docs](https://tailwindcss.com/)
- [Vite Docs](https://vitejs.dev/)
- [React Router Docs](https://reactrouter.com/en/main)
- [Apollo Client Docs](https://www.apollographql.com/docs/react/)

---

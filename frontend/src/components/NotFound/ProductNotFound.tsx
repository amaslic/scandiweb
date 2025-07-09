import { useParams } from "react-router-dom";

export default function ProductNotFound() {
  const { sku } = useParams();

  return (
    <div className="notfound-container">
      <h1 className="notfound-title">Product Not Found</h1>
      <h2 className="notfound-subtitle">
        SKU: <span className="notfound-highlight">{sku}</span>
      </h2>
      <p className="notfound-message">
        The product you're looking for <span className="font-bold">doesn't exist</span> or may have been removed.
      </p>
    </div>
  );
}

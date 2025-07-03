import { useCallback, useEffect, useState } from "react";
import { ChevronLeft, ChevronRight } from "lucide-react";

interface Props {
  images: string[];
}

function ProductGallery({ images }: Props) {
  const [currentIndex, setCurrentIndex] = useState(0);

  const nextImage = useCallback(() => {
    setCurrentIndex((prev) => (prev + 1) % images.length);
  }, [images.length]);

  const prevImage = useCallback(() => {
    setCurrentIndex((prev) => (prev - 1 + images.length) % images.length);
  }, [images.length]);

  useEffect(() => {
    const handleKey = (e: KeyboardEvent) => {
      if (e.key === "ArrowLeft") {
        prevImage();
      } else if (e.key === "ArrowRight") {
        nextImage();
      }
    };

    window.addEventListener("keydown", handleKey);
    return () => window.removeEventListener("keydown", handleKey);
  }, [nextImage, prevImage]);

  if (!images || images.length === 0) {
    return <div>No images available</div>;
  }

  return (
    <div className="gallery-wrapper" data-testid="product-gallery">
      <div className="gallery-thumbnails">
        {images.map((img, index) => (
          <img
            loading="lazy"
            key={index}
            src={img}
            alt={`Thumbnail ${index + 1}`}
            onClick={() => setCurrentIndex(index)}
            className={`gallery-thumbnail ${
              currentIndex === index ? "gallery-thumbnail-active" : ""
            }`}
          />
        ))}
      </div>

      <div className="gallery-main">
        <img
          loading="lazy"
          src={images[currentIndex]}
          alt={`Image ${currentIndex + 1}`}
          className="gallery-main-image"
        />

        <button
          onClick={prevImage}
          className="gallery-arrow gallery-arrow-left"
          aria-label="Previous image"
        >
          <ChevronLeft />
        </button>

        <button
          onClick={nextImage}
          className="gallery-arrow gallery-arrow-right"
          aria-label="Next image"
        >
          <ChevronRight />
        </button>
      </div>
    </div>
  );
}

export default ProductGallery;

import { useCallback, useEffect, useState } from "react";
import { ChevronLeft, ChevronRight } from "lucide-react";

interface Props {
  images: string[];
}

function ProductGallery({ images }: Props) {
  const [currentIndex, setCurrentIndex] = useState(0);
  const length = images.length;

  const nextImage = useCallback(() => {
    if (currentIndex < length - 1) {
      setCurrentIndex(currentIndex + 1);
    }
  }, [currentIndex, length]);

  const prevImage = useCallback(() => {
    if (currentIndex > 0) {
      setCurrentIndex(currentIndex - 1);
    }
  }, [currentIndex]);

  useEffect(() => {
    const handleKey = (e: KeyboardEvent) => {
      if (length <= 1) return;
      if (e.key === "ArrowLeft") {
        prevImage();
      } else if (e.key === "ArrowRight") {
        nextImage();
      }
    };

    window.addEventListener("keydown", handleKey);
    return () => window.removeEventListener("keydown", handleKey);
  }, [nextImage, prevImage, length]);

  if (length === 0) {
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

        {/* Only show arrows if there’s more than one image */}
        {length > 1 && (
          <>
            {/* Left arrow hidden on first image */}
            {currentIndex > 0 && (
              <button
                onClick={prevImage}
                className="gallery-arrow gallery-arrow-left"
                aria-label="Previous image"
              >
                <ChevronLeft />
              </button>
            )}

            {/* Right arrow hidden on last image */}
            {currentIndex < length - 1 && (
              <button
                onClick={nextImage}
                className="gallery-arrow gallery-arrow-right"
                aria-label="Next image"
              >
                <ChevronRight />
              </button>
            )}
          </>
        )}
      </div>
    </div>
  );
}

export default ProductGallery;

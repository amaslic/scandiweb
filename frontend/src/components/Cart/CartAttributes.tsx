interface Props {
  attributes: any[];
}

function CartAttributes({ attributes }: Props) {
  console.log(attributes);
  return (
    <div className="cart-attribute-block">
      {attributes.map((attr, index) => {
        const kebabAttr = attr.name.toLowerCase().replace(/\s+/g, "-");

        return (
          <div key={index} data-testid={`cart-item-attribute-${kebabAttr}`}>
            <p className="cart-attribute-label">{attr.name}:</p>
            <div className="flex gap-2">
              {attr.items &&
                attr.items.map((itemOpt: any) => {
                  /* const kebabValue = itemOpt.value
                    .toLowerCase()
                    .replace(/\s+/g, "-"); */
                  const isSelected = attr.selectedItem?.value === itemOpt.value;

                  return attr.name.toLowerCase() === "color" ? (
                    <span
                      key={itemOpt.id}
                      className={`cart-attribute-color rounded-sm ${
                        isSelected
                          ? "ring-2 ring-offset-2 ring-blue-500"
                          : "border border-gray-300"
                      }`}
                      style={{ backgroundColor: itemOpt.value }}
                      title={itemOpt.displayValue}
                      data-testid={`cart-item-attribute-${kebabAttr}-${kebabAttr}${
                        isSelected ? "-selected" : ""
                      }`}
                    />
                  ) : (
                    <span
                      key={itemOpt.id}
                      className={`cart-attribute-value px-2 py-1 rounded text-sm font-medium ${
                        isSelected
                          ? "border-2 border-blue-500 bg-blue-50"
                          : "border border-gray-300"
                      }`}
                      title={itemOpt.displayValue}
                      data-testid={`cart-item-attribute-${kebabAttr}-${kebabAttr}${
                        isSelected ? "-selected" : ""
                      }`}
                    >
                      {itemOpt.value}
                    </span>
                  );
                })}
            </div>
          </div>
        );
      })}
    </div>
  );
}

export default CartAttributes;

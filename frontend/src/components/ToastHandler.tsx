import { toast, ToastContainer, type ToastOptions } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

const config: ToastOptions = { position: "top-right", autoClose: 3000 };

const ToastHandler = {
  successProductAdd: (name: string) => {
    toast.success(`Product ${name} added to cart.`, config);
  },

  successProductRemove: (name: string) => {
    toast.success(`Product ${name} removed from cart.`, config);
  },

  errorProduct: (name: string) => {
    toast.error(`Error adding ${name} in cart.`, config);
  },

  successOrder: (msg: string) => {
    toast.success(msg, config);
  },

  errorOrder: () => {
    toast.error(`Error adding order`, config);
  },
};

export const ToastRoot = () => <ToastContainer />;
export default ToastHandler;

import axios from "../config/axiosInstance";
import Swal from "sweetalert2";

export default function useDeleteIp() {
    const deleteIP = async (id) => {
        try {
            let res = await axios.delete(`/app/ips/${id}`);
            Swal.fire({
                title: "Success!",
                text: res.data.message,
                icon: "success",
            });
            return true;
        } catch (error) {
            console.error('Failed to delete IP:', error);
            Swal.fire({
                title: "Error!",
                text: error.response.data.error_message,
                icon: "error",
                confirmButtonText: "Try Again",
            });
            return false;
        }
    };

    return {deleteIP}; // <-- This is important!
}
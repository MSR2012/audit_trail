import {useState} from "react";
import axios from "../config/axiosInstance";
import Swal from "sweetalert2";

const useAdIp = () => {
    const addIp = async (form, setForm, setIps) => {
        const success = handleInputErrors(form);
        if (!success) {
            return;
        }

        try {
            const res = await axios.post("/app/ips",
                new URLSearchParams({
                    ip_address: form.ip_address,
                    label: form.label,
                    comment: form.comment,
                })
            );

            console.log(res);
            const data = res.data;
            setIps((prev) => [...prev, data]);
            setForm({ip_address: '', label: '', comment: ''});

            Swal.fire({
                title: "Success!",
                text: data.message,
                icon: "success",
            });
        } catch (error) {
            console.log(error);
            Swal.fire({
                title: "Error!",
                text: error.response.data.error_message,
                icon: "error",
                confirmButtonText: "Try Again",
            });
        } finally {

        }
    };

    return {addIp};
};
export default useAdIp;

function handleInputErrors(form) {
    if (!form.ip_address || !form.label) {
        Swal.fire({
            title: "Error!",
            text: "Please fill all fields.",
            icon: "error",
            confirmButtonText: "Try Again",
        });
        return false;
    }

    return true;
}

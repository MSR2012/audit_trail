import {useState} from "react";
import axios from "../config/axiosInstance";
import Swal from "sweetalert2";
import {useNavigate} from "react-router-dom";

const useUpdateIp = () => {
    const navigate = useNavigate();
    const updateIp = async (id, form, setForm) => {
        const success = handleInputErrors(form);
        if (!success) {
            return;
        }

        try {
            const res = await axios.put('/app/ips/' + id,
                new URLSearchParams({
                    ip_address: form.ip_address,
                    label: form.label,
                    comment: form.comment,
                })
            );

            console.log(res);
            const data = res.data;
            setForm({ip_address: '', label: '', comment: ''});

            Swal.fire({
                title: "Success!",
                text: data.message,
                icon: "success",
            });
            navigate("/ips");
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

    return {updateIp};
};
export default useUpdateIp;

function handleInputErrors(form) {
    if (!form.label) {
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

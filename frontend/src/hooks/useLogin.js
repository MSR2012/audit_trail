import {useState} from "react";
import {useAuthContext} from "../context/AuthContext";
import axios from "../config/axiosInstance";
import {useNavigate} from "react-router-dom";
import Swal from "sweetalert2";

const useLogin = () => {
    const [loading, setLoading] = useState(false);
    const {setAuthUser} = useAuthContext();
    const navigate = useNavigate();

    const login = async (email, password) => {
        const success = handleInputErrors(email, password);
        if (!success) {
            return;
        }

        setLoading(true);
        try {
            const res = await axios.post("/auth/login",
                new URLSearchParams({
                    email: email,
                    password: password
                })
            );

            const data = res.data;
            localStorage.setItem("auth-user", JSON.stringify(data));
            localStorage.setItem("token", data.token);
            localStorage.setItem("refresh", data.refresh_token);
            setAuthUser(data);

            Swal.fire({
                title: "Success!",
                text: data.message,
                icon: "success",
            });

            navigate("/ips");
        } catch (error) {
            Swal.fire({
                title: "Error!",
                text: error.response.data.error_message,
                icon: "error",
                confirmButtonText: "Try Again",
            });
        } finally {
            setLoading(false);
        }
    };

    return {loading, login};
};
export default useLogin;

function handleInputErrors(username, password) {
    if (!username || !password) {
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

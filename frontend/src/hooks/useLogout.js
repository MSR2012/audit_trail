import {useAuthContext} from "../context/AuthContext";
import axios from "../config/axiosInstance";
import {useNavigate} from "react-router-dom";
import Swal from "sweetalert2";

const useLogout = () => {
    const {setAuthUser} = useAuthContext();
    const navigate = useNavigate();

    const logout = async () => {
        try {
            await axios.post("/auth/logout");
        } catch (error) {
        } finally {
            localStorage.setItem("auth-user", null);
            localStorage.setItem("token", null);
            localStorage.setItem("refresh", null);
            setAuthUser(null);

            Swal.fire({
                title: "Success!",
                text: "Logout successfully!",
                icon: "success",
            });

            navigate("/login");
        }
    };

    return {logout};
};
export default useLogout;
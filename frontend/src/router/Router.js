import {createBrowserRouter} from "react-router-dom";
import Main from "../layout/Main";
import Login from "../pages/Login";
import IpList from "../pages/IpList";
import IpDetail from "../pages/IpDetail";
import AuditLogs from "../pages/AuditLogs";
import {useAuthContext} from "../context/AuthContext";

const ProtectRoute = ({children}) => {
    const {authUser} = useAuthContext();

    console.log(authUser);

    if (authUser !== null) {
        return children;
    } else {
        return <Login/>;
    }
};

const router = createBrowserRouter([
    {
        path: "/",
        element: (
            <ProtectRoute>
                <Main/>
            </ProtectRoute>
        ),
        children: [
            {
                path: "/ips",
                element: (
                    <ProtectRoute>
                        <IpList/>
                    </ProtectRoute>
                ),
            },
            {
                path: "/ips/:id",
                element: (
                    <ProtectRoute>
                        <IpDetail/>
                    </ProtectRoute>
                ),
            },
            {
                path: "/logs",
                element: (
                    <ProtectRoute>
                        <AuditLogs/>
                    </ProtectRoute>
                ),
            },
        ],
    },
    {
        path: "/login",
        element: <Login/>,
    },
]);

export default router;

import {useEffect, useState} from 'react';
import {Link, useSearchParams} from 'react-router-dom';
import axios from '../config/axiosInstance';

export default function IpList() {
    const [logs, setLogs] = useState([]);
    const [searchParams, setSearchParams] = useSearchParams();

    // fetch all IPs – assuming GET /app/ips returns list (adjust if your API differs)
    useEffect(() => {
        let params = {};
        if (searchParams.get("ip_address")) {
            params.ip_address = searchParams.get("ip_address");
        } else if (searchParams.get("user_id")) {
            params.user_id = searchParams.get("user_id");
        }
        console.log(params.ip_address);
        console.log(params.user_id);
        (async () => {
            try {
                const {data} = await axios.get('/app/audit_log', {
                    params: params
                });
                console.log(data);
                setLogs(data); // keep flexible
            } catch {
                // handle
            }
        })();
    }, []);

    function actions(actionId) {
        if (actionId == 1) {
            return "Login";
        } else if (actionId == 2) {
            return "Logout";
        } else if (actionId == 3) {
            return "Create";
        } else if (actionId == 4) {
            return "Update";
        } else if (actionId == 5) {
            return "Delete";
        }

        return '';
    }

    return (
        <div className="space-y-8">
            <h2 className="text-xl font-semibold">Audit logs</h2>
            <table className="w-full border">
                <thead className="bg-gray-100">
                <tr>
                    <th className="p-2 border">User name</th>
                    <th className="p-2 border">IP</th>
                    <th className="p-2 border">Action</th>
                    <th className="p-2 border">Changes</th>
                    <th className="p-2 border">Changes made at</th>
                </tr>
                </thead>
                <tbody>
                {logs.map((log) => (
                    <tr key={log._id || log.id} className="border hover:bg-gray-50">
                        <td className="p-2 border">{log.user_name}</td>
                        <td className="p-2 border">{log.ip_address}</td>
                        <td className="p-2 border">{actions(log.action)}</td>
                        <td className="p-2 border">{log.changes}</td>
                        <td className="p-2 border">{log.changes_made_at}</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}

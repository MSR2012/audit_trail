import {useEffect, useState} from 'react';
import {Link} from 'react-router-dom';
import axios from '../config/axiosInstance';
import useAddIp from "../hooks/useAddIp";
import authContext, {useAuthContext} from "../context/AuthContext";
import useDeleteIp from "../hooks/useDeleteIp";

export default function IpList() {
    const [ips, setIps] = useState([]);
    const [form, setForm] = useState({ip_address: '', label: '', comment: ''});
    const {addIp} = useAddIp();
    const {authUser} = useAuthContext();
    const {deleteIP} = useDeleteIp();

    useEffect(() => {
        (async () => {
            try {
                const {data} = await axios.get('/app/ips');
                setIps(data.rows ?? data);
            } catch (error) {
                console.log(error);
                // handle
            }
        })();
    }, []);

    async function addNewIp(e) {
        e.preventDefault();
        await addIp(form, setForm, setIps);
    }

    async function deleteOldIp(e) {
        e.preventDefault();
        const id = e.target.value;
        const success = await deleteIP(id);
        if (success) {
            setIps(prev => prev.filter(ip => (ip._id || ip.id) !== id));
        }
    }

    return (
        <div className="space-y-8">
            <h2 className="text-xl font-semibold">IP Addresses</h2>

            <form onSubmit={addNewIp} className="flex gap-3 flex-wrap">
                <input placeholder="IP address" value={form.ip_address}
                       onChange={(e) => setForm({...form, ip_address: e.target.value})}
                       className="border p-2 rounded grow" required/>
                <input placeholder="Label" value={form.label}
                       onChange={(e) => setForm({...form, label: e.target.value})} className="border p-2 rounded grow"/>
                <input placeholder="Comment" value={form.comment}
                       onChange={(e) => setForm({...form, comment: e.target.value})}
                       className="border p-2 rounded grow"/>
                <button className="bg-green-600 text-white px-4 rounded">Add</button>
            </form>

            <table className="w-full border">
                <thead className="bg-gray-100">
                <tr>
                    <th className="p-2 border">IP</th>
                    <th className="p-2 border">Label</th>
                    <th className="p-2 border">Comment</th>
                    <th className="p-2 border">Actions</th>
                </tr>
                </thead>
                <tbody>
                {ips.map((ip) => (

                    <tr key={ip._id || ip.id} className="border hover:bg-gray-50">
                        <td className="p-2 border">
                            {
                                authUser.user.role == 2 || authUser.user.id == ip.user_id ? (
                                    <Link to={`/ips/${ip._id || ip.id}`}
                                          className="text-blue-600">{ip.ip_address}</Link>
                                ) : (
                                    ip.ip_address
                                )
                            }

                        </td>
                        <td className="p-2 border">{ip.label}</td>
                        <td className="p-2 border">{ip.comment}</td>
                        <td className="p-2 border">
                            <Link to={`/logs?ip_address=${ip.ip_address}`} className="text-blue-600">
                                View by IP Address
                            </Link><br/>
                            <Link to={`/logs?user_id=${ip.user_id}`} className="text-blue-600">
                                View by User
                            </Link>
                            {
                                authUser.user.role == 2 ? (
                                    <button onClick={deleteOldIp} value={ip.id}
                                            className="ml-auto text-red-400">Delete</button>
                                ) : (
                                    ''
                                )
                            }
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}

import {useEffect, useState} from 'react';
import {useParams} from 'react-router-dom';
import axios from '../config/axiosInstance';
import useUpdateIp from "../hooks/useUpdateIp";

export default function IpDetail() {
    const {id} = useParams();
    const [form, setForm] = useState({ip_address: '', label: '', comment: ''});
    const {updateIp} = useUpdateIp();

    useEffect(() => {
        (async () => {
            const {data} = await axios.get(`/app/ips/${id}`);
            setForm(data);
        })();
    }, [id]);

    async function updateOldIp(e) {
        e.preventDefault();
        await updateIp(id, form, setForm);
    }

    return (
        <div className="space-y-8">
            <h2 className="text-xl font-semibold">IP Addresses edit</h2>

            <form onSubmit={updateOldIp} className="flex gap-3 flex-wrap">
                <input placeholder="IP address" value={form.ip_address}
                       onChange={(e) => setForm({...form, ip_address: e.target.value})}
                       className="border p-2 rounded grow" disabled/>
                <input placeholder="Label" value={form.label}
                       onChange={(e) => setForm({...form, label: e.target.value})} className="border p-2 rounded grow"/>
                <input placeholder="Comment" value={form.comment}
                       onChange={(e) => setForm({...form, comment: e.target.value})}
                       className="border p-2 rounded grow"/>
                <button className="bg-green-600 text-white px-4 rounded">Update</button>
            </form>
        </div>
    );
}
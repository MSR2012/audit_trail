import {useEffect, useState} from 'react';
import {Link} from 'react-router-dom';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import Table from 'react-bootstrap/Table';
import Badge from 'react-bootstrap/Badge';
import Spinner from 'react-bootstrap/Spinner';
import Alert from 'react-bootstrap/Alert';
import ButtonGroup from 'react-bootstrap/ButtonGroup';
import Dropdown from 'react-bootstrap/Dropdown';
import axios from '../config/axiosInstance';
import useAddIp from "../hooks/useAddIp";
import {useAuthContext} from "../context/AuthContext";
import useDeleteIp from "../hooks/useDeleteIp";

export default function IpList() {
    const [ips, setIps] = useState([]);
    const [form, setForm] = useState({ip_address: '', label: '', comment: ''});
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [addingIp, setAddingIp] = useState(false);
    const {addIp} = useAddIp();
    const {authUser} = useAuthContext();
    const {deleteIP} = useDeleteIp();

    useEffect(() => {
        (async () => {
            try {
                setLoading(true);
                const {data} = await axios.get('/app/ips');
                setIps(data.rows ?? data);
                setError('');
            } catch (error) {
                setError('Failed to fetch IP addresses. Please try again.');
                console.error('Error fetching IPs:', error);
            } finally {
                setLoading(false);
            }
        })();
    }, []);

    async function addNewIp(e) {
        e.preventDefault();
        setAddingIp(true);
        try {
            await addIp(form, setForm, setIps);
        } finally {
            setAddingIp(false);
        }
    }

    async function deleteOldIp(id) {
        if (window.confirm('Are you sure you want to delete this IP address?')) {
            const success = await deleteIP(id);
            if (success) {
                setIps(prev => prev.filter(ip => (ip._id || ip.id) !== id));
            }
        }
    }

    const canEdit = (ip) => {
        return authUser.user.role === 2 || authUser.user.id === ip.user_id;
    };

    const isAdmin = () => {
        return authUser.user.role === 2;
    };

    return (
        <Container fluid className="py-4">
            <Row>
                <Col>
                    <Card className="shadow-sm border-0">
                        <Card.Header className="bg-primary text-white d-flex align-items-center justify-content-between">
                            <div className="d-flex align-items-center">
                                <i className="fas fa-network-wired me-2"></i>
                                <h4 className="mb-0">IP Address Management</h4>
                            </div>
                            <Badge bg="light" text="dark">
                                {ips.length} IP{ips.length !== 1 ? 's' : ''}
                            </Badge>
                        </Card.Header>
                        <Card.Body>
                            {/* Add New IP Form */}
                            <Card className="mb-4 border-success">
                                <Card.Header className="bg-success text-white">
                                    <i className="fas fa-plus me-2"></i>
                                    Add New IP Address
                                </Card.Header>
                                <Card.Body>
                                    <Form onSubmit={addNewIp}>
                                        <Row>
                                            <Col md={3}>
                                                <Form.Group className="mb-3">
                                                    <Form.Label className="fw-semibold">
                                                        <i className="fas fa-network-wired me-1"></i>
                                                        IP Address *
                                                    </Form.Label>
                                                    <Form.Control
                                                        type="text"
                                                        placeholder="192.168.1.1"
                                                        value={form.ip_address}
                                                        onChange={(e) => setForm({...form, ip_address: e.target.value})}
                                                        required
                                                        className="border-0 bg-light"
                                                    />
                                                </Form.Group>
                                            </Col>
                                            <Col md={3}>
                                                <Form.Group className="mb-3">
                                                    <Form.Label className="fw-semibold">
                                                        <i className="fas fa-tag me-1"></i>
                                                        Label
                                                    </Form.Label>
                                                    <Form.Control
                                                        type="text"
                                                        placeholder="Office Network"
                                                        value={form.label}
                                                        onChange={(e) => setForm({...form, label: e.target.value})}
                                                        className="border-0 bg-light"
                                                    />
                                                </Form.Group>
                                            </Col>
                                            <Col md={4}>
                                                <Form.Group className="mb-3">
                                                    <Form.Label className="fw-semibold">
                                                        <i className="fas fa-comment me-1"></i>
                                                        Comment
                                                    </Form.Label>
                                                    <Form.Control
                                                        type="text"
                                                        placeholder="Additional notes"
                                                        value={form.comment}
                                                        onChange={(e) => setForm({...form, comment: e.target.value})}
                                                        className="border-0 bg-light"
                                                    />
                                                </Form.Group>
                                            </Col>
                                            <Col md={2} className="d-flex align-items-end">
                                                <Button
                                                    type="submit"
                                                    variant="success"
                                                    disabled={addingIp}
                                                    className="w-100 mb-3"
                                                >
                                                    {addingIp ? (
                                                        <>
                                                            <Spinner animation="border" size="sm" className="me-2" />
                                                            Adding...
                                                        </>
                                                    ) : (
                                                        <>
                                                            <i className="fas fa-plus me-2"></i>
                                                            Add IP
                                                        </>
                                                    )}
                                                </Button>
                                            </Col>
                                        </Row>
                                    </Form>
                                </Card.Body>
                            </Card>

                            {/* IP List Table */}
                            {loading ? (
                                <div className="text-center py-5">
                                    <Spinner animation="border" variant="primary" />
                                    <p className="mt-2 text-muted">Loading IP addresses...</p>
                                </div>
                            ) : error ? (
                                <Alert variant="danger">
                                    <i className="fas fa-exclamation-triangle me-2"></i>
                                    {error}
                                </Alert>
                            ) : ips.length === 0 ? (
                                <Alert variant="info">
                                    <i className="fas fa-info-circle me-2"></i>
                                    No IP addresses found. Add your first IP address above.
                                </Alert>
                            ) : (
                                <div className="table-responsive">
                                    <Table striped hover className="mb-0">
                                        <thead className="table-dark">
                                            <tr>
                                                <th>
                                                    <i className="fas fa-network-wired me-2"></i>
                                                    IP Address
                                                </th>
                                                <th>
                                                    <i className="fas fa-tag me-2"></i>
                                                    Label
                                                </th>
                                                <th>
                                                    <i className="fas fa-comment me-2"></i>
                                                    Comment
                                                </th>
                                                <th className="text-center">
                                                    <i className="fas fa-cogs me-2"></i>
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {ips.map((ip) => (
                                                <tr key={ip._id || ip.id}>
                                                    <td>
                                                        <code className="bg-light px-2 py-1 rounded fw-bold">
                                                            {canEdit(ip) ? (
                                                                <Link 
                                                                    to={`/ips/${ip._id || ip.id}`}
                                                                    className="text-decoration-none"
                                                                >
                                                                    {ip.ip_address}
                                                                </Link>
                                                            ) : (
                                                                ip.ip_address
                                                            )}
                                                        </code>
                                                    </td>
                                                    <td>
                                                        {ip.label ? (
                                                            <Badge bg="info" className="fs-6">
                                                                {ip.label}
                                                            </Badge>
                                                        ) : (
                                                            <span className="text-muted">No label</span>
                                                        )}
                                                    </td>
                                                    <td>
                                                        <span className="text-muted">
                                                            {ip.comment || 'No comment'}
                                                        </span>
                                                    </td>
                                                    <td className="text-center">
                                                        <ButtonGroup size="sm">
                                                            <Button
                                                                as={Link}
                                                                to={`/logs?ip_address=${ip.ip_address}`}
                                                                variant="outline-primary"
                                                            >
                                                                <i className="fas fa-eye me-1"></i>
                                                                View by IP
                                                            </Button>
                                                            <Button
                                                                as={Link}
                                                                to={`/logs?user_id=${ip.user_id}`}
                                                                variant="outline-secondary"
                                                            >
                                                                <i className="fas fa-user me-1"></i>
                                                                View by User
                                                            </Button>
                                                            {isAdmin() && (
                                                                <Button
                                                                    variant="outline-danger"
                                                                    onClick={() => deleteOldIp(ip.id)}
                                                                >
                                                                    <i className="fas fa-trash me-1"></i>
                                                                    Delete
                                                                </Button>
                                                            )}
                                                        </ButtonGroup>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </Table>
                                </div>
                            )}
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </Container>
    );
}

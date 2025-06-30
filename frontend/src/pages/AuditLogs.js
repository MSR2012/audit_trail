import {useEffect, useState} from 'react';
import {Link, useSearchParams} from 'react-router-dom';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Table from 'react-bootstrap/Table';
import Badge from 'react-bootstrap/Badge';
import Spinner from 'react-bootstrap/Spinner';
import Alert from 'react-bootstrap/Alert';
import axios from '../config/axiosInstance';

export default function AuditLogs() {
    const [logs, setLogs] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [searchParams] = useSearchParams();

    useEffect(() => {
        let params = {};
        if (searchParams.get("ip_address")) {
            params.ip_address = searchParams.get("ip_address");
        } else if (searchParams.get("user_id")) {
            params.user_id = searchParams.get("user_id");
        }

        (async () => {
            try {
                setLoading(true);
                const {data} = await axios.get('/app/audit_log', {
                    params: params
                });
                setLogs(data);
                setError('');
            } catch (err) {
                setError('Failed to fetch audit logs. Please try again.');
                console.error('Error fetching audit logs:', err);
            } finally {
                setLoading(false);
            }
        })();
    }, [searchParams]);

    function getActionDetails(actionId) {
        const actions = {
            1: { name: "Login", variant: "success", icon: "fas fa-sign-in-alt" },
            2: { name: "Logout", variant: "secondary", icon: "fas fa-sign-out-alt" },
            3: { name: "Create", variant: "primary", icon: "fas fa-plus" },
            4: { name: "Update", variant: "warning", icon: "fas fa-edit" },
            5: { name: "Delete", variant: "danger", icon: "fas fa-trash" }
        };
        return actions[actionId] || { name: "Unknown", variant: "dark", icon: "fas fa-question" };
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }

    return (
        <Container fluid className="py-4">
            <Row>
                <Col>
                    <Card className="shadow-sm border-0">
                        <Card.Header className="bg-primary text-white d-flex align-items-center">
                            <i className="fas fa-clipboard-list me-2"></i>
                            <h4 className="mb-0">Audit Logs</h4>
                            {searchParams.get("ip_address") && (
                                <Badge bg="light" text="dark" className="ms-auto">
                                    IP: {searchParams.get("ip_address")}
                                </Badge>
                            )}
                            {searchParams.get("user_id") && (
                                <Badge bg="light" text="dark" className="ms-auto">
                                    User ID: {searchParams.get("user_id")}
                                </Badge>
                            )}
                        </Card.Header>
                        <Card.Body className="p-0">
                            {loading ? (
                                <div className="text-center py-5">
                                    <Spinner animation="border" variant="primary" />
                                    <p className="mt-2 text-muted">Loading audit logs...</p>
                                </div>
                            ) : error ? (
                                <Alert variant="danger" className="m-3">
                                    <i className="fas fa-exclamation-triangle me-2"></i>
                                    {error}
                                </Alert>
                            ) : logs.length === 0 ? (
                                <Alert variant="info" className="m-3">
                                    <i className="fas fa-info-circle me-2"></i>
                                    No audit logs found for the specified criteria.
                                </Alert>
                            ) : (
                                <div className="table-responsive">
                                    <Table striped hover className="mb-0">
                                        <thead className="table-dark">
                                            <tr>
                                                <th>
                                                    <i className="fas fa-user me-2"></i>
                                                    User Name
                                                </th>
                                                <th>
                                                    <i className="fas fa-network-wired me-2"></i>
                                                    IP Address
                                                </th>
                                                <th>
                                                    <i className="fas fa-cog me-2"></i>
                                                    Action
                                                </th>
                                                <th>
                                                    <i className="fas fa-edit me-2"></i>
                                                    Changes
                                                </th>
                                                <th>
                                                    <i className="fas fa-clock me-2"></i>
                                                    Timestamp
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {logs.map((log, index) => {
                                                const actionDetails = getActionDetails(log.action);
                                                return (
                                                    <tr key={log._id || log.id || index}>
                                                        <td className="fw-semibold">{log.user_name || 'N/A'}</td>
                                                        <td>
                                                            <code className="bg-light px-2 py-1 rounded">
                                                                {log.ip_address}
                                                            </code>
                                                        </td>
                                                        <td>
                                                            <Badge bg={actionDetails.variant} className="d-flex align-items-center justify-content-center">
                                                                <i className={`${actionDetails.icon} me-1`}></i>
                                                                {actionDetails.name}
                                                            </Badge>
                                                        </td>
                                                        <td>
                                                            <span className="text-muted small">
                                                                {log.changes || 'No changes recorded'}
                                                            </span>
                                                        </td>
                                                        <td className="text-muted small">
                                                            {formatDate(log.changes_made_at)}
                                                        </td>
                                                    </tr>
                                                );
                                            })}
                                        </tbody>
                                    </Table>
                                </div>
                            )}
                        </Card.Body>
                        {logs.length > 0 && (
                            <Card.Footer className="bg-light text-muted small">
                                <i className="fas fa-info-circle me-1"></i>
                                Showing {logs.length} audit log{logs.length !== 1 ? 's' : ''}
                            </Card.Footer>
                        )}
                    </Card>
                </Col>
            </Row>
        </Container>
    );
}

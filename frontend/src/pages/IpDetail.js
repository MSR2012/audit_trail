import {useEffect, useState} from 'react';
import {useParams, useNavigate} from 'react-router-dom';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import Spinner from 'react-bootstrap/Spinner';
import Alert from 'react-bootstrap/Alert';
import ButtonGroup from 'react-bootstrap/ButtonGroup';
import axios from '../config/axiosInstance';
import useUpdateIp from "../hooks/useUpdateIp";

export default function IpDetail() {
    const {id} = useParams();
    const navigate = useNavigate();
    const [form, setForm] = useState({ip_address: '', label: '', comment: ''});
    const [loading, setLoading] = useState(true);
    const [updating, setUpdating] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const {updateIp} = useUpdateIp();

    useEffect(() => {
        (async () => {
            try {
                setLoading(true);
                const {data} = await axios.get(`/app/ips/${id}`);
                setForm(data);
                setError('');
            } catch (error) {
                setError('Failed to fetch IP details. Please try again.');
                console.error('Error fetching IP details:', error);
            } finally {
                setLoading(false);
            }
        })();
    }, [id]);

    async function updateOldIp(e) {
        e.preventDefault();
        setUpdating(true);
        setError('');
        setSuccess('');
        
        try {
            await updateIp(id, form, setForm);
            setSuccess('IP address updated successfully!');
            setTimeout(() => setSuccess(''), 3000);
        } catch (error) {
            setError('Failed to update IP address. Please try again.');
            console.error('Error updating IP:', error);
        } finally {
            setUpdating(false);
        }
    }

    const handleGoBack = () => {
        navigate('/ips');
    };

    if (loading) {
        return (
            <Container fluid className="py-4">
                <div className="text-center py-5">
                    <Spinner animation="border" variant="primary" />
                    <p className="mt-2 text-muted">Loading IP details...</p>
                </div>
            </Container>
        );
    }

    return (
        <Container fluid className="py-4">
            <Row>
                <Col lg={8} className="mx-auto">
                    <Card className="shadow-sm border-0">
                        <Card.Header className="bg-warning text-white d-flex align-items-center justify-content-between">
                            <div className="d-flex align-items-center">
                                <i className="fas fa-edit me-2"></i>
                                <h4 className="mb-0">Edit IP Address</h4>
                            </div>
                            <Button
                                variant="outline-light"
                                size="sm"
                                onClick={handleGoBack}
                            >
                                <i className="fas fa-arrow-left me-1"></i>
                                Back to List
                            </Button>
                        </Card.Header>
                        <Card.Body className="p-4">
                            {error && (
                                <Alert variant="danger" className="mb-4">
                                    <i className="fas fa-exclamation-triangle me-2"></i>
                                    {error}
                                </Alert>
                            )}
                            
                            {success && (
                                <Alert variant="success" className="mb-4">
                                    <i className="fas fa-check-circle me-2"></i>
                                    {success}
                                </Alert>
                            )}

                            <Form onSubmit={updateOldIp}>
                                <Row>
                                    <Col md={12}>
                                        <Form.Group className="mb-4">
                                            <Form.Label className="fw-semibold">
                                                <i className="fas fa-network-wired me-2"></i>
                                                IP Address
                                            </Form.Label>
                                            <Form.Control
                                                type="text"
                                                value={form.ip_address}
                                                onChange={(e) => setForm({...form, ip_address: e.target.value})}
                                                disabled
                                                size="lg"
                                                className="bg-light border-0"
                                            />
                                            <Form.Text className="text-muted">
                                                <i className="fas fa-info-circle me-1"></i>
                                                IP address cannot be modified after creation
                                            </Form.Text>
                                        </Form.Group>
                                    </Col>
                                </Row>
                                
                                <Row>
                                    <Col md={6}>
                                        <Form.Group className="mb-4">
                                            <Form.Label className="fw-semibold">
                                                <i className="fas fa-tag me-2"></i>
                                                Label
                                            </Form.Label>
                                            <Form.Control
                                                type="text"
                                                placeholder="Enter a descriptive label"
                                                value={form.label}
                                                onChange={(e) => setForm({...form, label: e.target.value})}
                                                size="lg"
                                                className="border-0 bg-light"
                                            />
                                            <Form.Text className="text-muted">
                                                A short, descriptive name for this IP address
                                            </Form.Text>
                                        </Form.Group>
                                    </Col>
                                    <Col md={6}>
                                        <Form.Group className="mb-4">
                                            <Form.Label className="fw-semibold">
                                                <i className="fas fa-comment me-2"></i>
                                                Comment
                                            </Form.Label>
                                            <Form.Control
                                                as="textarea"
                                                rows={3}
                                                placeholder="Enter additional notes or comments"
                                                value={form.comment}
                                                onChange={(e) => setForm({...form, comment: e.target.value})}
                                                className="border-0 bg-light"
                                            />
                                            <Form.Text className="text-muted">
                                                Optional additional information about this IP address
                                            </Form.Text>
                                        </Form.Group>
                                    </Col>
                                </Row>
                                
                                <div className="d-flex justify-content-between">
                                    <Button
                                        variant="outline-secondary"
                                        onClick={handleGoBack}
                                        disabled={updating}
                                    >
                                        <i className="fas fa-times me-2"></i>
                                        Cancel
                                    </Button>
                                    <Button
                                        type="submit"
                                        variant="warning"
                                        disabled={updating}
                                        className="px-4"
                                    >
                                        {updating ? (
                                            <>
                                                <Spinner animation="border" size="sm" className="me-2" />
                                                Updating...
                                            </>
                                        ) : (
                                            <>
                                                <i className="fas fa-save me-2"></i>
                                                Update IP Address
                                            </>
                                        )}
                                    </Button>
                                </div>
                            </Form>
                        </Card.Body>
                        <Card.Footer className="bg-light text-muted small">
                            <i className="fas fa-clock me-1"></i>
                            Last modified: {new Date().toLocaleString()}
                        </Card.Footer>
                    </Card>
                </Col>
            </Row>
        </Container>
    );
}
import {useState} from 'react';
import useLogin from '../hooks/useLogin';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import Alert from 'react-bootstrap/Alert';
import LoadingSpinner from '../components/LoadingSpinner';

function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');

    const {loading, login} = useLogin();

    const handleSubmit = async (e) => {
        e.preventDefault();
        await login(email, password)
    };

    return (
        <Container fluid className="d-flex align-items-center justify-content-center min-vh-100 bg-light">
            <Row className="w-100">
                <Col xs={12} sm={8} md={6} lg={4} className="mx-auto">
                    <Card className="shadow-lg border-0">
                        <Card.Header className="bg-primary text-white text-center py-4">
                            <h2 className="mb-0">
                                <i className="fas fa-shield-alt me-2"></i>
                                Audit Trail
                            </h2>
                            <p className="mb-0 mt-2 opacity-75">Secure Access Portal</p>
                        </Card.Header>
                        <Card.Body className="p-4">
                            <Form onSubmit={handleSubmit}>
                                <Form.Group className="mb-3" controlId="email">
                                    <Form.Label className="fw-semibold">
                                        <i className="fas fa-envelope me-2"></i>
                                        Email Address
                                    </Form.Label>
                                    <Form.Control
                                        name="email"
                                        type="email"
                                        value={email}
                                        placeholder="Enter your email address"
                                        onChange={(e) => setEmail(e.target.value)}
                                        size="lg"
                                        required
                                        className="border-0 bg-light"
                                    />
                                </Form.Group>
                                <Form.Group className="mb-4" controlId="password">
                                    <Form.Label className="fw-semibold">
                                        <i className="fas fa-lock me-2"></i>
                                        Password
                                    </Form.Label>
                                    <Form.Control
                                        name="password"
                                        type="password"
                                        value={password}
                                        placeholder="Enter your password"
                                        onChange={(e) => setPassword(e.target.value)}
                                        size="lg"
                                        required
                                        className="border-0 bg-light"
                                    />
                                </Form.Group>
                                <div className="d-grid">
                                    <Button
                                        type="submit"
                                        variant="primary"
                                        size="lg"
                                        disabled={loading}
                                        className="fw-semibold"
                                    >
                                        {loading ? (
                                            <>
                                                <LoadingSpinner />
                                                <span className="ms-2">Signing In...</span>
                                            </>
                                        ) : (
                                            <>
                                                <i className="fas fa-sign-in-alt me-2"></i>
                                                Sign In
                                            </>
                                        )}
                                    </Button>
                                </div>
                            </Form>
                        </Card.Body>
                        <Card.Footer className="text-center py-3 bg-light">
                            <small className="text-muted">
                                <i className="fas fa-info-circle me-1"></i>
                                Secure login protected by encryption
                            </small>
                        </Card.Footer>
                    </Card>
                </Col>
            </Row>
        </Container>
    );
}

export default Login;

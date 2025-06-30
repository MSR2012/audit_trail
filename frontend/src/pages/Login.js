import {useState} from 'react';
import useLogin from '../hooks/useLogin';
import Container from 'react-bootstrap/Container';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
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
        <Container>
            <h1 className="text-3xl font-bold text-center text-gray-800">
                Login to <span className="text-blue-600">Audit Trail</span>
            </h1>
            <Form onSubmit={handleSubmit}>
                <Form.Group className="mb-3" controlId="email">
                    <Form.Label>Email address</Form.Label>
                    <Form.Control
                        name="email"
                        type="email"
                        value={email}
                        placeholder="name@example.com"
                        onChange={(e) => setEmail(e.target.value)}
                    />
                </Form.Group>
                <Form.Group className="mb-3" controlId="password">
                    <Form.Label>Password</Form.Label>
                    <Form.Control
                        name="password"
                        type="password"
                        value={password}
                        placeholder="password"
                        onChange={(e) => setPassword(e.target.value)}
                    />
                </Form.Group>
                <Button
                    type="submit"
                    variant="primary"
                    className={`${loading ? 'opacity-50 cursor-not-allowed' : ''}`}
                    disabled={loading}
                >
                    {loading ? (
                        <LoadingSpinner></LoadingSpinner>
                    ) : (
                        'Login'
                    )}
                </Button>
            </Form>
        </Container>
    );
}

export default Login;

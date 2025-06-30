import {Navbar, Container, Nav} from 'react-bootstrap';
import AuthContext, {useAuthContext} from "../context/AuthContext";
import useLogout from "../hooks/useLogout";


function nav() {
    const {authUser} = useAuthContext();
    const {logout} = useLogout();

    return (
        <Navbar expand="lg" className="bg-light shadow-sm py-3">
            <Container>
                <Navbar.Brand className="fw-bold text-primary fs-4">Audit Trail</Navbar.Brand>
                <Navbar.Toggle aria-controls="basic-navbar-nav"/>
                <Navbar.Collapse id="basic-navbar-nav">
                    <Nav className="me-auto gap-3">
                        <Nav.Link href="/ips" className="text-dark fw-semibold">IP Addresses</Nav.Link>
                        <Nav.Link href="/logs" className="text-dark fw-semibold">Audit Logs</Nav.Link>
                    </Nav>
                    <div className="d-flex align-items-center gap-3">
                        <span className="text-muted">{authUser.user.name}</span>
                        <button onClick={logout} className="btn btn-outline-danger btn-sm">Logout</button>
                    </div>
                </Navbar.Collapse>
            </Container>
        </Navbar>
    );
}

export default nav;
import Container from 'react-bootstrap/Container';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import AuthContext, {useAuthContext} from "../context/AuthContext";
import useLogout from "../hooks/useLogout";


function nav() {
    const {authUser} = useAuthContext();
    const {logout} = useLogout();

    return (
        <Navbar expand="lg" className="bg-body-tertiary">
            <Container>
                <Navbar.Brand>Audit Trail</Navbar.Brand>
                <Navbar.Toggle aria-controls="basic-navbar-nav"/>
                <Navbar.Collapse id="basic-navbar-nav">
                    <Nav className="me-auto">
                        <Nav.Link href="/ips">Ips</Nav.Link>
                        <Nav.Link href="/logs">Audit Logs</Nav.Link>
                        <button onClick={logout} className="ml-auto text-red-400">{authUser.user.name} Logout</button>
                    </Nav>
                </Navbar.Collapse>
            </Container>
        </Navbar>
    );
}

export default nav;
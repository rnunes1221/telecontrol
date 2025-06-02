// auth.js

(async function validarTokenJWT() {
    const token = localStorage.getItem('jwt_token');

    if (!token) {
    redirecionarParaLogin();
    return;
    }
    /*
    try {
        await axios.get('http://localhost:8080/products', {
            headers: {
            'Authorization': `Bearer ${token}`
            }
        });
    } catch (error) {
        if (error.response && error.response.status === 401) {
            localStorage.removeItem('jwt_token');
            redirecionarParaLogin();
        } else {
            console.error('Erro inesperado ao validar token:', error);
        }
    }
    */

    function redirecionarParaLogin() {
        window.location.href = 'login.html';
    }
})();

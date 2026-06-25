export function isAuthenticated() {
    return !!localStorage.getItem('token');
}

export function getUser() {
    const userStr = localStorage.getItem('usuario');
    if (!userStr) return null;
    try {
        return JSON.parse(userStr);
    } catch (e) {
        return null;
    }
}

export function requireAuth(expectedPerfil = null) {
    if (!isAuthenticated()) {
        window.location.href = 'login.html';
        return false;
    }

    if (expectedPerfil) {
        const user = getUser();
        if (!user || user.perfil !== expectedPerfil) {
            // Se tentar acessar uma tela com perfil errado, manda pra home apropriada
            if (user && user.perfil === 'admin') {
                window.location.href = 'admin-dashboard.html';
            } else {
                window.location.href = 'cliente-dashboard.html';
            }
            return false;
        }
    }

    return true;
}

export function requireGuest() {
    if (isAuthenticated()) {
        const user = getUser();
        if (user && user.perfil === 'admin') {
            window.location.href = 'admin-dashboard.html';
        } else {
            window.location.href = 'cliente-dashboard.html';
        }
        return false;
    }
    return true;
}

export function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('usuario');
    window.location.href = 'login.html';
}

// Vincula o botão de logout se existir na tela
document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.getElementById('btn-logout');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            logout();
        });
    }
    
    // Mostra o nome do usuário logado se existir o placeholder
    const userNameEl = document.getElementById('display-user-name');
    const userRoleEl = document.getElementById('display-user-role');
    const user = getUser();
    if (user) {
        if (userNameEl) userNameEl.textContent = user.nome;
        if (userRoleEl) userRoleEl.textContent = user.perfil;
    }
});

export const API_BASE_URL = 'http://127.0.0.1:8082';

export async function apiFetch(endpoint, options = {}) {
    const token = localStorage.getItem('token');
    
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers
    };

    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const config = {
        ...options,
        headers
    };

    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, config);
        
        // Se a API retornar 401 Unauthorized e não for a rota de login, o token expirou
        if (response.status === 401 && endpoint !== '/auth/login') {
            localStorage.removeItem('token');
            localStorage.removeItem('usuario');
            window.location.href = 'login.html';
            return null;
        }

        const data = await response.json();
        
        // Retorna erro estruturado caso não seja sucesso
        if (!response.ok) {
            throw { status: response.status, data };
        }

        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// Utilitário para exibir erros na tela sem recarregar a página
export function showError(elementId, errorObj) {
    const el = document.getElementById(elementId);
    if (!el) return;
    
    let message = 'Ocorreu um erro desconhecido.';
    
    if (errorObj.data) {
        if (errorObj.data.messages) {
            // Pode ser um objeto com múltiplos erros de validação
            message = Object.values(errorObj.data.messages).join('<br>');
        } else if (errorObj.data.error) {
            message = errorObj.data.error;
        } else if (errorObj.data.message) {
            message = errorObj.data.message;
        }
    } else if (errorObj.message) {
        message = errorObj.message;
    }

    el.innerHTML = `
        <div class="bg-red-500/15 border border-red-500/35 text-red-300 p-4 rounded-xl shadow-lg mt-4">
            <span class="font-bold">Aviso:</span> <br> ${message}
        </div>
    `;
    el.classList.remove('hidden');
}

export function hideError(elementId) {
    const el = document.getElementById(elementId);
    if (el) {
        el.innerHTML = '';
        el.classList.add('hidden');
    }
}

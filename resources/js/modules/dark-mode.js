// Dark Mode System - Corrigido para funcionar com os botões da navbar
class DarkModeManager {
    constructor() {
        this.init();
    }

    init() {
        // Verificar preferência salva ou do sistema
        const savedTheme = localStorage.getItem('darkMode');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedTheme === 'true') {
            this.setTheme('dark');
        } else if (savedTheme === 'false') {
            this.setTheme('light');
        } else if (systemPrefersDark) {
            this.setTheme('dark');
        } else {
            this.setTheme('light');
        }

        // Listener para mudanças do sistema
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('darkMode')) {
                this.setTheme(e.matches ? 'dark' : 'light');
            }
        });

        this.setupToggleButtons();
    }

    setTheme(theme) {
        const html = document.documentElement;
        const body = document.body;
        
        if (theme === 'dark') {
            html.classList.add('dark');
            body.classList.add('dark');
            localStorage.setItem('darkMode', 'true');
        } else {
            html.classList.remove('dark');
            body.classList.remove('dark');
            localStorage.setItem('darkMode', 'false');
        }
        
        this.updateToggleButtons(theme);
    }

    toggleTheme() {
        const isDark = document.documentElement.classList.contains('dark');
        this.setTheme(isDark ? 'light' : 'dark');
    }

    setupToggleButtons() {
        // Configurar botões existentes na navbar
        const desktopBtn = document.getElementById('darkModeToggle');
        const mobileBtn = document.getElementById('darkModeToggleMobile');
        
        if (desktopBtn) {
            desktopBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleTheme();
            });
        }
        
        if (mobileBtn) {
            mobileBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleTheme();
            });
        }
    }

    updateToggleButtons(theme) {
        const desktopIcon = document.getElementById('darkModeIcon');
        const mobileIcon = document.getElementById('darkModeIconMobile');
        
        const iconClass = theme === 'dark' ? 
            'M10 2a6 6 0 100 12 6 6 0 000-12zM10 18a8 8 0 110-16 8 8 0 010 16z' : 
            'M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z';
        
        if (desktopIcon) {
            desktopIcon.innerHTML = `<path d="${iconClass}"></path>`;
        }
        
        if (mobileIcon) {
            mobileIcon.innerHTML = `<path d="${iconClass}"></path>`;
        }
        
        // Atualizar título dos botões
        const desktopBtn = document.getElementById('darkModeToggle');
        const mobileBtn = document.getElementById('darkModeToggleMobile');
        
        const title = theme === 'dark' ? 'Ativar modo claro' : 'Ativar modo escuro';
        
        if (desktopBtn) {
            desktopBtn.setAttribute('title', title);
        }
        
        if (mobileBtn) {
            mobileBtn.setAttribute('title', title);
        }
    }

    // Método público para resetar o tema
    resetTheme() {
        localStorage.removeItem('darkMode');
        this.setTheme('light');
    }

    // Método público para obter o tema atual
    getCurrentTheme() {
        return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    }
}

// Inicializar quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    window.darkModeManager = new DarkModeManager();
});

// Função global para resetar o tema (útil para debug)
window.resetDarkMode = function() {
    if (window.darkModeManager) {
        window.darkModeManager.resetTheme();
        console.log('Modo escuro resetado para claro');
    }
};

// Função global para alternar tema
window.toggleDarkMode = function() {
    if (window.darkModeManager) {
        window.darkModeManager.toggleTheme();
        console.log('Tema alternado para:', window.darkModeManager.getCurrentTheme());
    }
};

// Exportar para uso global
window.DarkModeManager = DarkModeManager;

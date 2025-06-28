// Dark Mode System
class DarkModeManager {
    constructor() {
        this.init();
    }

    init() {
        // Verificar preferência salva ou do sistema
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedTheme) {
            this.setTheme(savedTheme);
        } else if (systemPrefersDark) {
            this.setTheme('dark');
        } else {
            this.setTheme('light');
        }

        // Listener para mudanças do sistema
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                this.setTheme(e.matches ? 'dark' : 'light');
            }
        });

        this.setupToggleButton();
    }

    setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        this.updateToggleButton(theme);
    }

    toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    }

    setupToggleButton() {
        // Criar botão de toggle se não existir
        if (!document.getElementById('theme-toggle')) {
            const nav = document.querySelector('.navbar-nav');
            if (nav) {
                const toggleButton = document.createElement('li');
                toggleButton.className = 'nav-item';
                toggleButton.innerHTML = `
                    <button id="theme-toggle" class="btn btn-link nav-link border-0 bg-transparent" title="Alternar tema">
                        <i class="bi bi-moon-fill" id="theme-icon"></i>
                    </button>
                `;
                nav.appendChild(toggleButton);
            }
        }

        // Adicionar event listener
        const toggleBtn = document.getElementById('theme-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggleTheme());
        }
    }

    updateToggleButton(theme) {
        const icon = document.getElementById('theme-icon');
        if (icon) {
            if (theme === 'dark') {
                icon.className = 'bi bi-sun-fill';
            } else {
                icon.className = 'bi bi-moon-fill';
            }
        }
    }
}

// Inicializar quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    new DarkModeManager();
});

// Exportar para uso global
window.DarkModeManager = DarkModeManager;

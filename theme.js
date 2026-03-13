// Fichier: theme.js - Version améliorée
class ThemeManager {
    constructor() {
        this.themeToggleBtn = document.getElementById('themeToggle');
        this.htmlTag = document.documentElement;
        this.themeIcon = this.themeToggleBtn?.querySelector('i');
        this.init();
    }

    init() {
        // Appliquer le thème sauvegardé
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            this.applyDarkTheme();
        }

        // Ajouter l'écouteur d'événement
        if (this.themeToggleBtn) {
            this.themeToggleBtn.addEventListener('click', () => this.toggleTheme());
        }

        // Écouter les changements de thème système
        this.watchSystemTheme();
    }

    applyDarkTheme() {
        this.htmlTag.setAttribute('data-bs-theme', 'dark');
        if (this.themeIcon) {
            this.themeIcon.className = 'fa-solid fa-sun';
        }
        if (this.themeToggleBtn) {
            this.themeToggleBtn.classList.replace('btn-warning', 'btn-light');
        }
    }

    applyLightTheme() {
        this.htmlTag.setAttribute('data-bs-theme', 'light');
        if (this.themeIcon) {
            this.themeIcon.className = 'fa-solid fa-moon';
        }
        if (this.themeToggleBtn) {
            this.themeToggleBtn.classList.replace('btn-light', 'btn-warning');
        }
    }

    toggleTheme() {
        if (this.htmlTag.getAttribute('data-bs-theme') === 'light') {
            this.applyDarkTheme();
            localStorage.setItem('theme', 'dark');
            this.showNotification('Thème sombre activé', '🌙');
        } else {
            this.applyLightTheme();
            localStorage.setItem('theme', 'light');
            this.showNotification('Thème clair activé', '☀️');
        }
    }

    watchSystemTheme() {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                if (e.matches) {
                    this.applyDarkTheme();
                } else {
                    this.applyLightTheme();
                }
            }
        });
    }

    showNotification(message, emoji) {
        // Créer une notification toast
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 p-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="toast show align-items-center text-white bg-${this.htmlTag.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'primary'} border-0">
                <div class="d-flex">
                    <div class="toast-body">
                        ${emoji} ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
}

// Initialiser le gestionnaire de thème
document.addEventListener('DOMContentLoaded', () => {
    new ThemeManager();
});
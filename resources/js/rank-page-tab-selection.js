const saveActiveTab = () => {
    const activeTab = document.querySelector('.nav-link.active');
    if (activeTab) {
        localStorage.setItem('rank-page-tab', activeTab.id);
    }
};

const restoreActiveTab = () => {
    const savedTabId = localStorage.getItem('rank-page-tab');
    const defaultTabId = 'complete-results-tab';

    // Remove classes de ativação de todas as abas
    document.querySelectorAll('.nav-link').forEach(tab => {
        tab.classList.remove('active');
    });

    document.querySelectorAll('.tab-pane').forEach(tabPane => {
        tabPane.classList.remove('show', 'active');
    });

    // Restaurar a aba salva no localStorage ou ativar a aba padrão
    const tabToActivate = document.getElementById(savedTabId) || document.getElementById(defaultTabId);

    if (tabToActivate) {
        tabToActivate.classList.add('active');
        const targetPane = document.querySelector(tabToActivate.dataset.bsTarget);
        if (targetPane) {
            targetPane.classList.add('show', 'active');
        }
    }
};

// Evento para salvar a aba ativa ao clicar
document.querySelectorAll('.nav-link').forEach(tab => {
    tab.addEventListener('click', saveActiveTab);
});

// Restaurar a aba ativa assim que a página estiver carregada
document.addEventListener('DOMContentLoaded', restoreActiveTab);

const saveActiveTab = () => {
    const activeTab = document.querySelector('.nav-link.active');
    if (activeTab) localStorage.setItem('rank-page-tab', activeTab.id);
};

const restoreActiveTab = () => {
    const savedTabId = localStorage.getItem('rank-page-tab');
    if (savedTabId) {
        const savedTab = document.getElementById(savedTabId);
        if (savedTab) savedTab.click();
    } else {
        const defaultTab = document.getElementById('complete-results-tab');
        if (defaultTab) defaultTab.click();
    }
};

document.querySelectorAll('.nav-link').forEach(tab => {
    tab.addEventListener('click', saveActiveTab);
});

document.addEventListener('DOMContentLoaded', restoreActiveTab);

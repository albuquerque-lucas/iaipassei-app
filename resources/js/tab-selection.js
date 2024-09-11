    const saveActiveTab = () => {
        const activeTab = document.querySelector('.nav-link.active');
        if (activeTab) {
            localStorage.setItem('activeTab', activeTab.id);
        }
    };

    const restoreActiveTab = () => {
        const savedTabId = localStorage.getItem('activeTab');
        if (savedTabId) {
            const savedTab = document.getElementById(savedTabId);
            if (savedTab) {
                savedTab.click();
            }
        } else {
            const defaultTab = document.getElementById('info-tab');
            if (defaultTab) {
                defaultTab.click();
            }
        }
    };

    document.querySelectorAll('.nav-link').forEach(tab => {
        tab.addEventListener('click', saveActiveTab);
    });

    document.addEventListener('DOMContentLoaded', restoreActiveTab);

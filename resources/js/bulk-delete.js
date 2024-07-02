document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.select-item');
    const bulkDeleteButton = document.getElementById('bulkDeleteButton');
    const selectedIdsInput = document.getElementById('selected_ids');

    function updateBulkDeleteButton() {
        const selectedIds = Array.from(itemCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        selectedIdsInput.value = JSON.stringify(selectedIds);
        bulkDeleteButton.disabled = selectedIds.length === 0;
    }

    selectAllCheckbox.addEventListener('change', function () {
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        updateBulkDeleteButton();
    });

    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteButton);
    });
});

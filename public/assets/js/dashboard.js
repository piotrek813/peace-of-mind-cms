function showArrayData(button) {
    const data = JSON.parse(button.dataset.array);
    const modal = button.nextElementSibling;
    modal.querySelector('.array-content').textContent = JSON.stringify(data, null, 2);
    modal.showModal();
} 
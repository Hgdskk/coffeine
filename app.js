// Подтверждение удаления + клиентская валидация
document.addEventListener('submit', function (e) {
    const form = e.target;

    // Модалка подтверждения удаления
    if (form.classList.contains('js-delete-form')) {
        if (!confirm('Точно удалить?')) {
            e.preventDefault();
        }
    }

    // Валидация формы поставщика
    if (form.id === 'supplierForm') {
        const name = form.querySelector('[name="name"]');
        if (!name.value.trim()) {
            e.preventDefault();
            alert('Название не может быть пустым');
        }
    }

    // Валидация формы регистрации
    if (form.id === 'registerForm') {
        const email = form.querySelector('[name="email"]').value;
        const pass  = form.querySelector('[name="password"]').value;
        const re    = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!re.test(email)) {
            e.preventDefault();
            return alert('Введите корректный email');
        }
        if (pass.length < 6) {
            e.preventDefault();
            return alert('Пароль минимум 6 символов');
        }
    }
});
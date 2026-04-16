document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('.form-suspension');

    forms.forEach((form) => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            const button = form.querySelector('.btn-suspendre');

            if (button) {
                button.disabled = true;
                button.textContent = 'Suspension...';
            }

            try {
                const response = await fetch('/ecoridestudi/ecoride/public/index.php?url=traiterSuspension', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                const rawText = await response.text();
                console.log('Réponse brute du serveur :', rawText);

                let data;
                try {
                    data = JSON.parse(rawText);
                } catch (error) {
                    throw new Error('Réponse non JSON');
                }

                if (data.success) {
                    const row = form.closest('tr');
                    if (row) {
                        row.remove();
                    }
                    alert(data.message || 'Compte suspendu avec succès.');
                } else {
                    alert(data.message || 'Erreur lors de la suspension.');
                    if (button) {
                        button.disabled = false;
                        button.textContent = 'Suspendre';
                    }
                }
            } catch (error) {
                console.error('Erreur fetch suspension :', error);
                alert('Erreur serveur ou réponse invalide.');
                if (button) {
                    button.disabled = false;
                    button.textContent = 'Suspendre';
                }
            }
        });
    });
});
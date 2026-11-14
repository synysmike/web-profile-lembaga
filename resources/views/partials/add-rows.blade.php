<script>
    document.querySelectorAll('[data-add]').forEach((button) => {
        button.addEventListener('click', () => {
            const list = document.getElementById(button.getAttribute('data-add'));
            const rows = list.querySelectorAll('[data-row]');
            const last = rows[rows.length - 1];

            if (!last) {
                return;
            }

            const copy = last.cloneNode(true);
            const next = rows.length;
            copy.querySelectorAll('input, textarea').forEach((input) => {
                input.name = input.name
                    .replace(/new]\[\d+]/, 'new][' + next + ']')
                    .replace(/^new\[\d+]/, 'new[' + next + ']');

                if (input.type === 'file') {
                    input.value = '';
                } else if (input.type === 'checkbox') {
                    input.checked = input.name.endsWith('[is_visible]');
                } else if (input.type === 'number' && input.name.includes('[position]')) {
                    input.value = String(next + 1);
                } else {
                    input.value = '';
                }
            });
            list.appendChild(copy);
        });
    });
</script>

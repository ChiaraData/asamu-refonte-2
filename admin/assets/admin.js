const adminEscapeHtml = (value) => String(value)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');

function adminPlainTextToHtml(value) {
    return String(value).trim().split(/\n\s*\n/).filter(Boolean)
        .map((paragraph) => `<p>${adminEscapeHtml(paragraph).replace(/\n/g, '<br>')}</p>`).join('');
}

function adminSanitizePreview(value) {
    const template = document.createElement('template');
    template.innerHTML = String(value);
    const allowed = new Set(['P', 'BR', 'STRONG', 'EM', 'UL', 'OL', 'LI', 'A']);
    template.content.querySelectorAll('*').forEach((element) => {
        if (!allowed.has(element.tagName)) {
            element.replaceWith(...element.childNodes);
            return;
        }
        const href = element.tagName === 'A' ? (element.getAttribute('href') || '') : '';
        [...element.attributes].forEach((attribute) => element.removeAttribute(attribute.name));
        if (element.tagName === 'A') {
            if (/^(https?:\/\/|mailto:|tel:)/i.test(href)) {
                element.setAttribute('href', href);
                element.setAttribute('rel', 'noopener noreferrer');
            }
        }
    });
    return template.innerHTML;
}

function initialiseRichEditors(scope = document) {
    scope.querySelectorAll('textarea[data-rich-editor]').forEach((textarea) => {
        if (textarea.dataset.richEditorReady === 'true') return;
        textarea.dataset.richEditorReady = 'true';

        const wrapper = document.createElement('section');
        wrapper.className = 'admin-rich-editor';
        wrapper.innerHTML = `
            <div class="admin-rich-editor-toolbar" role="toolbar" aria-label="Mise en forme du texte">
                <button type="button" data-editor-command="bold" title="Gras"><strong>G</strong></button>
                <button type="button" data-editor-command="italic" title="Italique"><em>I</em></button>
                <button type="button" data-editor-command="insertUnorderedList" title="Liste à puces">• Liste</button>
                <button type="button" data-editor-command="insertOrderedList" title="Liste numérotée">1. Liste</button>
                <button type="button" data-editor-command="createLink" title="Ajouter un lien">Lien</button>
                <button type="button" data-editor-command="removeFormat" title="Retirer la mise en forme">Effacer</button>
            </div>
            <div class="admin-rich-editor-canvas" contenteditable="true" role="textbox" aria-multiline="true"></div>
            <div class="admin-rich-editor-preview"><span>Aperçu en direct</span><div></div></div>
        `;
        const canvas = wrapper.querySelector('.admin-rich-editor-canvas');
        const preview = wrapper.querySelector('.admin-rich-editor-preview > div');
        const initialValue = textarea.value;
        canvas.innerHTML = adminSanitizePreview(initialValue.includes('<') ? initialValue : adminPlainTextToHtml(initialValue));

        const synchronise = () => {
            const safeHtml = adminSanitizePreview(canvas.innerHTML);
            textarea.value = safeHtml;
            preview.innerHTML = safeHtml || '<p class="admin-rich-empty">Le texte apparaîtra ici.</p>';
        };
        synchronise();
        canvas.addEventListener('input', synchronise);
        canvas.addEventListener('blur', synchronise);
        wrapper.querySelectorAll('[data-editor-command]').forEach((button) => {
            button.addEventListener('click', () => {
                canvas.focus();
                const command = button.dataset.editorCommand;
                if (command === 'createLink') {
                    const url = window.prompt('Adresse du lien (https://, mailto: ou tel:)');
                    if (url && /^(https?:\/\/|mailto:|tel:)/i.test(url.trim())) {
                        document.execCommand('createLink', false, url.trim());
                    }
                } else {
                    document.execCommand(command, false);
                }
                synchronise();
            });
        });
        textarea.after(wrapper);
        textarea.classList.add('admin-rich-editor-source');
    });
}

document.addEventListener('click', (event) => {
    const addButton = event.target.closest('[data-add-row]');
    if (addButton) {
        const name = addButton.dataset.addRow;
        const template = document.querySelector(`template[data-template="${name}"]`);
        const target = document.querySelector(`[data-rows="${name}"]`);
        if (template && target) {
            target.appendChild(template.content.cloneNode(true));
            const addedRow = target.lastElementChild;
            initialiseRichEditors(addedRow);
            const field = addedRow?.querySelector('input, textarea, select');
            if (field) field.focus();
        }
        return;
    }

    const removeButton = event.target.closest('[data-remove-row]');
    if (removeButton) {
        const row = removeButton.closest('.admin-repeat-row, .admin-podium-row');
        if (row && confirm('Supprimer cet élément ?')) row.remove();
        return;
    }

    const confirmButton = event.target.closest('[data-confirm]');
    if (confirmButton && !confirm(confirmButton.dataset.confirm || 'Confirmer cette action ?')) {
        event.preventDefault();
    }

    if (event.target.closest('[data-menu-toggle]')) {
        document.body.classList.toggle('admin-menu-open');
    }
});

document.addEventListener('change', (event) => {
    if (event.target.matches('[data-role-select]')) {
        const sectionRights = document.querySelector('[data-section-rights]');
        if (sectionRights) sectionRights.hidden = event.target.value !== 'section_editor';
    }
    if (event.target.matches('[data-media-input]')) {
        const dropzone = event.target.closest('[data-media-dropzone]');
        const file = event.target.files?.[0];
        if (dropzone && file) dropzone.classList.add('has-file');
        if (dropzone && file) dropzone.querySelector('strong').textContent = file.name;
    }
});

document.querySelectorAll('[data-media-dropzone]').forEach((dropzone) => {
    ['dragenter', 'dragover'].forEach((eventName) => dropzone.addEventListener(eventName, (event) => {
        event.preventDefault();
        dropzone.classList.add('is-dragging');
    }));
    ['dragleave', 'drop'].forEach((eventName) => dropzone.addEventListener(eventName, (event) => {
        event.preventDefault();
        dropzone.classList.remove('is-dragging');
    }));
    dropzone.addEventListener('drop', (event) => {
        const input = dropzone.querySelector('[data-media-input]');
        const files = event.dataTransfer?.files;
        if (input && files?.length) {
            input.files = files;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    });
});

document.querySelectorAll('form.admin-form').forEach((form) => {
    form.addEventListener('submit', () => {
        form.querySelectorAll('.admin-rich-editor-canvas').forEach((canvas) => canvas.dispatchEvent(new Event('blur')));
    });
});

initialiseRichEditors();

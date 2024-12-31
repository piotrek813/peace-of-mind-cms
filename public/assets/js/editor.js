document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.rich-text-field').forEach(richTextField => {
        const quill = new Quill(`#${richTextField.dataset.name}`, {
            modules: {
                toolbar: [
                    [{ header: [1, 2, false] }],
                    ['bold', 'italic'],
                    ['link', 'blockquote', 'code-block', 'image'],
                    [{ list: 'ordered' }, { list: 'bullet' }]
                ]
            },
            theme: 'snow'
        });

        quill.setContents(JSON.parse(richTextField.dataset.content));

        document.querySelector('form').addEventListener('submit', (e) => {
            e.preventDefault();
            console.log(quill.getContents().ops);
            e.formData.append(`${richTextField.dataset.name}[value]`, JSON.stringify(quill.getContents().ops));
        });
    });
});

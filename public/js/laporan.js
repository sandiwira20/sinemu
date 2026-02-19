document.addEventListener('DOMContentLoaded', () => {
    const dropArea = document.querySelector('.report-upload');
    if (!dropArea) return;

    const fileInput = document.querySelector('#evidence');

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.add('is-dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.remove('is-dragover');
        });
    });

    dropArea.addEventListener('drop', e => {
        const files = e.dataTransfer.files;
        if (fileInput) fileInput.files = files;
    });
});

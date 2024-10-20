document.addEventListener('DOMContentLoaded', () => {
    const catscapeContainer = document.getElementById('catscape-container');
    const catscapeFrame = document.getElementById('catscape-frame');
    const catscapeUrl = document.getElementById('catscape-url');
    const catscapeClose = document.getElementById('catscape-close');
    const catscapeStatus = document.getElementById('catscape-status');
    const catscapeMetaTitle = document.getElementById('catscape-meta-title');

    function openCatscape(url) {
        catscapeContainer.classList.remove('hidden');
        catscapeFrame.src = url;
        catscapeUrl.value = url;
        catscapeStatus.textContent = 'Loading...';
        catscapeMetaTitle.textContent = '';
    }

    function closeCatscape() {
        catscapeContainer.classList.add('hidden');
        catscapeFrame.src = 'about:blank';
    }

    document.querySelectorAll('.catscape-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            openCatscape(link.href);
        });
    });

    catscapeClose.addEventListener('click', closeCatscape);

    catscapeFrame.addEventListener('load', () => {
        catscapeStatus.textContent = 'Done';
        try {
            const frameDocument = catscapeFrame.contentDocument || catscapeFrame.contentWindow.document;
            const metaTitle = frameDocument.querySelector('title');
            if (metaTitle) {
                catscapeMetaTitle.textContent = metaTitle.textContent;
            }
        } catch (error) {
            console.error('Error accessing iframe content:', error);
        }
    });
});

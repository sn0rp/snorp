document.addEventListener('DOMContentLoaded', () => {
    const catscapeContainer = document.getElementById('catscape-container');
    const catscapeFrame = document.getElementById('catscape-frame');
    const catscapeUrl = document.getElementById('catscape-url');
    const catscapeClose = document.getElementById('catscape-close');
    const catscapeOpenMain = document.getElementById('catscape-open-main');
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

    function openInMainWindow() {
        const currentUrl = catscapeFrame.src;
        window.location.href = currentUrl;
        closeCatscape();
    }

    document.querySelectorAll('.catscape-link, .external-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            if (link.classList.contains('external-link')) {
                window.open(link.href, '_blank');
            } else if (link.getAttribute('href') === 'index.php') {
                window.location.href = link.href;
            } else {
                openCatscape(link.href);
            }
        });
    });

    catscapeClose.addEventListener('click', closeCatscape);
    catscapeOpenMain.addEventListener('click', openInMainWindow);

    function handleIframeLoad() {
        catscapeStatus.textContent = 'Done';
        try {
            const frameDocument = catscapeFrame.contentDocument || catscapeFrame.contentWindow.document;
            const metaTitle = frameDocument.querySelector('title');
            if (metaTitle) {
                catscapeMetaTitle.textContent = metaTitle.textContent;
            }
            
            // Add event listeners to all links in the iframe
            frameDocument.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    openCatscape(link.href);
                });
            });
        } catch (error) {
            console.error('Error accessing iframe content:', error);
        }
    }

    catscapeFrame.addEventListener('load', handleIframeLoad);
});

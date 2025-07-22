@props(['show' => false, 'message' => 'Carregando...'])

<div id="loading-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
     style="background: rgba(0,0,0,0.5); z-index: 9999; {{ $show ? '' : 'display: none !important;' }}">
    <div class="bg-white rounded p-4 text-center shadow">
        <div class="spinner-border text-primary mb-3" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="fw-bold">{{ $message }}</div>
    </div>
</div>

<script>
window.showLoading = function(message = 'Carregando...') {
    const overlay = document.getElementById('loading-overlay');
    const messageEl = overlay.querySelector('.fw-bold');
    if (messageEl) messageEl.textContent = message;
    overlay.style.display = 'flex';
};

window.hideLoading = function() {
    const overlay = document.getElementById('loading-overlay');
    overlay.style.display = 'none';
};

// Auto-show em forms que demoram
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[data-loading]');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const message = form.getAttribute('data-loading') || 'Processando...';
            showLoading(message);
        });
    });
});
</script>

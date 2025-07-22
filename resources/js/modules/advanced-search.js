// Sistema de Busca Avançada
class AdvancedSearchManager {
    constructor() {
        this.filters = {};
        this.searchHistory = JSON.parse(localStorage.getItem('searchHistory') || '[]');
        this.savedFilters = JSON.parse(localStorage.getItem('savedFilters') || '{}');
        this.init();
    }

    init() {
        this.createAdvancedSearchInterface();
        this.setupEventListeners();
        this.loadSavedFilters();
    }

    createAdvancedSearchInterface() {
        // Verificar se já existe
        if (document.getElementById('advanced-search-container')) return;

        // Encontrar o container de filtros existente
        const existingFilters = document.querySelector('.row.g-3.mb-3.align-items-end');
        if (!existingFilters) return;

        // Criar container para busca avançada
        const advancedContainer = document.createElement('div');
        advancedContainer.id = 'advanced-search-container';
        advancedContainer.className = 'mb-3';
        
        advancedContainer.innerHTML = `
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-search me-2 text-primary"></i>
                        <h6 class="mb-0">Busca Avançada</h6>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary" id="toggle-search" title="Expandir/Recolher">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success" id="save-filter" title="Salvar Filtro">
                            <i class="bi bi-bookmark"></i>
                        </button>
                        <button type="button" class="btn btn-outline-info" id="load-filter" title="Carregar Filtro">
                            <i class="bi bi-folder-open"></i>
                        </button>
                        <button type="button" class="btn btn-outline-warning" id="clear-all" title="Limpar Tudo">
                            <i class="bi bi-eraser"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body collapse" id="advanced-search-body">
                    <div class="row g-3">
                        <!-- Busca por texto -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-search me-1"></i>Busca Inteligente
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="smart-search" 
                                       placeholder="Digite qualquer coisa...">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown">
                                    <i class="bi bi-clock-history"></i>
                                </button>
                                <ul class="dropdown-menu" id="search-history-dropdown">
                                    <li><h6 class="dropdown-header">Buscas Recentes</h6></li>
                                </ul>
                            </div>
                            <small class="text-muted">Ex: "São Paulo ontem", "viagem carro", "km > 100"</small>
                        </div>

                        <!-- Período personalizado -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-calendar-range me-1"></i>Período
                            </label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <select class="form-select form-select-sm" id="period-preset">
                                        <option value="">Personalizado</option>
                                        <option value="today">Hoje</option>
                                        <option value="yesterday">Ontem</option>
                                        <option value="this-week">Esta Semana</option>
                                        <option value="last-week">Semana Passada</option>
                                        <option value="this-month">Este Mês</option>
                                        <option value="last-month">Mês Passado</option>
                                        <option value="last-30-days">Últimos 30 dias</option>
                                        <option value="last-90-days">Últimos 90 dias</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <input type="date" class="form-control form-control-sm" id="date-from">
                                </div>
                                <div class="col-3">
                                    <input type="date" class="form-control form-control-sm" id="date-to">
                                </div>
                            </div>
                        </div>

                        <!-- Filtros de KM -->
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-speedometer me-1"></i>Quilometragem
                            </label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" 
                                           id="km-min" placeholder="Mín">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" 
                                           id="km-max" placeholder="Máx">
                                </div>
                            </div>
                            <div class="mt-1">
                                <small class="text-muted">Distância percorrida</small>
                            </div>
                        </div>

                        <!-- Status da viagem -->
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-flag me-1"></i>Status
                            </label>
                            <div class="d-flex gap-2 flex-wrap">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="status-concluida" value="concluida">
                                    <label class="form-check-label text-success" for="status-concluida">
                                        <i class="bi bi-check-circle"></i> Concluída
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="status-andamento" value="andamento">
                                    <label class="form-check-label text-warning" for="status-andamento">
                                        <i class="bi bi-clock"></i> Em andamento
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="status-agendada" value="agendada">
                                    <label class="form-check-label text-primary" for="status-agendada">
                                        <i class="bi bi-calendar"></i> Agendada
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Ordenação -->
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-sort-down me-1"></i>Ordenar por
                            </label>
                            <div class="row g-2">
                                <div class="col-8">
                                    <select class="form-select form-select-sm" id="sort-field">
                                        <option value="data">Data</option>
                                        <option value="destino">Destino</option>
                                        <option value="condutor">Condutor</option>
                                        <option value="km_percorrido">KM Percorrido</option>
                                        <option value="created_at">Data de Criação</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-select form-select-sm" id="sort-direction">
                                        <option value="desc">↓ Desc</option>
                                        <option value="asc">↑ Asc</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tags de filtros ativos -->
                    <div class="mt-3" id="active-filters-container" style="display: none;">
                        <label class="form-label fw-bold small">Filtros Ativos:</label>
                        <div id="active-filters-tags" class="d-flex gap-1 flex-wrap"></div>
                    </div>

                    <!-- Botões de ação -->
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn btn-primary btn-sm" id="apply-search">
                                <i class="bi bi-search me-1"></i>Aplicar Filtros
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="reset-search">
                                <i class="bi bi-arrow-clockwise me-1"></i>Resetar
                            </button>
                        </div>
                        <div class="text-muted small">
                            <span id="results-count">0 resultados encontrados</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Inserir antes dos filtros existentes
        existingFilters.parentNode.insertBefore(advancedContainer, existingFilters);
    }

    setupEventListeners() {
        // Toggle da busca avançada
        const toggleBtn = document.getElementById('toggle-search');
        const searchBody = document.getElementById('advanced-search-body');
        
        if (toggleBtn && searchBody) {
            toggleBtn.addEventListener('click', () => {
                const isExpanded = searchBody.classList.contains('show');
                if (isExpanded) {
                    searchBody.classList.remove('show');
                    toggleBtn.querySelector('i').className = 'bi bi-chevron-down';
                } else {
                    searchBody.classList.add('show');
                    toggleBtn.querySelector('i').className = 'bi bi-chevron-up';
                }
            });
        }

        // Busca inteligente
        const smartSearch = document.getElementById('smart-search');
        if (smartSearch) {
            smartSearch.addEventListener('input', debounce(() => {
                this.handleSmartSearch(smartSearch.value);
            }, 300));
        }

        // Período preset
        const periodPreset = document.getElementById('period-preset');
        if (periodPreset) {
            periodPreset.addEventListener('change', () => {
                this.handlePeriodPreset(periodPreset.value);
            });
        }

        // Aplicar filtros
        const applyBtn = document.getElementById('apply-search');
        if (applyBtn) {
            applyBtn.addEventListener('click', () => {
                this.applyFilters();
            });
        }

        // Resetar filtros
        const resetBtn = document.getElementById('reset-search');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                this.resetFilters();
            });
        }

        // Salvar filtro
        const saveBtn = document.getElementById('save-filter');
        if (saveBtn) {
            saveBtn.addEventListener('click', () => {
                this.saveCurrentFilter();
            });
        }

        // Carregar filtro
        const loadBtn = document.getElementById('load-filter');
        if (loadBtn) {
            loadBtn.addEventListener('click', () => {
                this.showSavedFilters();
            });
        }

        // Limpar tudo
        const clearBtn = document.getElementById('clear-all');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                this.clearAllFilters();
            });
        }

        // Histórico de busca
        this.setupSearchHistory();
    }

    handleSmartSearch(query) {
        if (!query) return;

        // Analisar a query e extrair filtros inteligentes
        const analyzedQuery = this.analyzeSearchQuery(query);
        
        // Aplicar filtros automaticamente
        if (analyzedQuery.filters) {
            Object.keys(analyzedQuery.filters).forEach(key => {
                const element = document.getElementById(key);
                if (element) {
                    element.value = analyzedQuery.filters[key];
                }
            });
        }

        // Atualizar tags de filtros ativos
        this.updateActiveFilterTags();
    }

    analyzeSearchQuery(query) {
        const filters = {};
        const terms = query.toLowerCase().split(' ');
        
        // Detectar datas relativas
        if (query.includes('hoje')) {
            filters['period-preset'] = 'today';
        } else if (query.includes('ontem')) {
            filters['period-preset'] = 'yesterday';
        } else if (query.includes('semana')) {
            filters['period-preset'] = 'this-week';
        }

        // Detectar KM
        const kmMatch = query.match(/km\s*[><=]\s*(\d+)/);
        if (kmMatch) {
            const operator = query.match(/[><=]/)[0];
            const value = kmMatch[1];
            
            if (operator === '>') {
                filters['km-min'] = value;
            } else if (operator === '<') {
                filters['km-max'] = value;
            }
        }

        // Detectar cidades/destinos conhecidos
        const cities = ['são paulo', 'rio de janeiro', 'belo horizonte', 'brasília'];
        const foundCity = cities.find(city => query.toLowerCase().includes(city));
        if (foundCity) {
            // Aplicar no filtro de destino existente
            const destinoInput = document.querySelector('input[name="destino"]');
            if (destinoInput) {
                destinoInput.value = foundCity;
            }
        }

        return { filters, originalQuery: query };
    }

    handlePeriodPreset(preset) {
        const dateFrom = document.getElementById('date-from');
        const dateTo = document.getElementById('date-to');
        
        if (!dateFrom || !dateTo) return;

        const today = new Date();
        let startDate, endDate;

        switch (preset) {
            case 'today':
                startDate = endDate = today;
                break;
            case 'yesterday':
                startDate = endDate = new Date(today.getTime() - 24 * 60 * 60 * 1000);
                break;
            case 'this-week':
                startDate = new Date(today.setDate(today.getDate() - today.getDay()));
                endDate = new Date();
                break;
            case 'last-week':
                const lastWeek = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                startDate = new Date(lastWeek.setDate(lastWeek.getDate() - lastWeek.getDay()));
                endDate = new Date(startDate.getTime() + 6 * 24 * 60 * 60 * 1000);
                break;
            case 'this-month':
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                endDate = new Date();
                break;
            case 'last-month':
                startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                break;
            case 'last-30-days':
                startDate = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
                endDate = new Date();
                break;
            case 'last-90-days':
                startDate = new Date(today.getTime() - 90 * 24 * 60 * 60 * 1000);
                endDate = new Date();
                break;
            default:
                return;
        }

        dateFrom.value = startDate.toISOString().split('T')[0];
        dateTo.value = endDate.toISOString().split('T')[0];
    }

    updateActiveFilterTags() {
        const container = document.getElementById('active-filters-container');
        const tagsContainer = document.getElementById('active-filters-tags');
        
        if (!container || !tagsContainer) return;

        const activeFilters = this.getActiveFilters();
        
        if (Object.keys(activeFilters).length === 0) {
            container.style.display = 'none';
            return;
        }

        container.style.display = 'block';
        tagsContainer.innerHTML = '';

        Object.entries(activeFilters).forEach(([key, value]) => {
            if (value) {
                const tag = document.createElement('span');
                tag.className = 'badge bg-primary me-1 mb-1';
                tag.innerHTML = `
                    ${this.getFilterLabel(key)}: ${value}
                    <button type="button" class="btn-close btn-close-white ms-1" 
                            style="font-size: 0.7em;" onclick="advancedSearch.removeFilter('${key}')"></button>
                `;
                tagsContainer.appendChild(tag);
            }
        });
    }

    getActiveFilters() {
        const filters = {};
        
        // Coletar todos os filtros ativos
        const smartSearch = document.getElementById('smart-search')?.value;
        const periodPreset = document.getElementById('period-preset')?.value;
        const dateFrom = document.getElementById('date-from')?.value;
        const dateTo = document.getElementById('date-to')?.value;
        const kmMin = document.getElementById('km-min')?.value;
        const kmMax = document.getElementById('km-max')?.value;
        
        if (smartSearch) filters.smartSearch = smartSearch;
        if (periodPreset) filters.periodPreset = periodPreset;
        if (dateFrom) filters.dateFrom = dateFrom;
        if (dateTo) filters.dateTo = dateTo;
        if (kmMin) filters.kmMin = kmMin;
        if (kmMax) filters.kmMax = kmMax;
        
        // Status checkboxes
        const statusCheckboxes = document.querySelectorAll('input[id^="status-"]:checked');
        if (statusCheckboxes.length > 0) {
            filters.status = Array.from(statusCheckboxes).map(cb => cb.value).join(',');
        }

        return filters;
    }

    getFilterLabel(key) {
        const labels = {
            smartSearch: 'Busca',
            periodPreset: 'Período',
            dateFrom: 'De',
            dateTo: 'Até',
            kmMin: 'KM Mín',
            kmMax: 'KM Máx',
            status: 'Status'
        };
        return labels[key] || key;
    }

    removeFilter(key) {
        const element = document.getElementById(key.replace(/([A-Z])/g, '-$1').toLowerCase());
        if (element) {
            if (element.type === 'checkbox') {
                element.checked = false;
            } else {
                element.value = '';
            }
        }
        this.updateActiveFilterTags();
    }

    applyFilters() {
        const filters = this.getActiveFilters();
        
        // Salvar no histórico
        const smartSearchValue = filters.smartSearch;
        if (smartSearchValue && !this.searchHistory.includes(smartSearchValue)) {
            this.searchHistory.unshift(smartSearchValue);
            this.searchHistory = this.searchHistory.slice(0, 10); // Manter apenas 10
            localStorage.setItem('searchHistory', JSON.stringify(this.searchHistory));
            this.updateSearchHistoryDropdown();
        }

        // Aplicar filtros na URL e recarregar
        const params = new URLSearchParams(window.location.search);
        
        Object.entries(filters).forEach(([key, value]) => {
            if (value) {
                params.set(key, value);
            } else {
                params.delete(key);
            }
        });

        // Recarregar página com novos parâmetros
        window.location.search = params.toString();
    }

    resetFilters() {
        // Limpar todos os campos
        document.getElementById('smart-search').value = '';
        document.getElementById('period-preset').value = '';
        document.getElementById('date-from').value = '';
        document.getElementById('date-to').value = '';
        document.getElementById('km-min').value = '';
        document.getElementById('km-max').value = '';
        
        // Desmarcar checkboxes
        document.querySelectorAll('input[id^="status-"]').forEach(cb => cb.checked = false);
        
        this.updateActiveFilterTags();
        
        // Limpar URL
        window.location.search = '';
    }

    saveCurrentFilter() {
        const filters = this.getActiveFilters();
        
        if (Object.keys(filters).length === 0) {
            alert('Nenhum filtro ativo para salvar.');
            return;
        }

        const name = prompt('Nome do filtro:');
        if (name) {
            this.savedFilters[name] = filters;
            localStorage.setItem('savedFilters', JSON.stringify(this.savedFilters));
            alert('Filtro salvo com sucesso!');
        }
    }

    showSavedFilters() {
        const savedFilterNames = Object.keys(this.savedFilters);
        
        if (savedFilterNames.length === 0) {
            alert('Nenhum filtro salvo encontrado.');
            return;
        }

        // Criar modal com filtros salvos
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-bookmark-fill me-2"></i>Filtros Salvos
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="list-group">
                            ${savedFilterNames.map(name => `
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>${name}</span>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" 
                                                onclick="advancedSearch.loadSavedFilter('${name}')">
                                            <i class="bi bi-download"></i> Carregar
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="advancedSearch.deleteSavedFilter('${name}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();

        modal.addEventListener('hidden.bs.modal', () => {
            document.body.removeChild(modal);
        });
    }

    loadSavedFilter(name) {
        const filters = this.savedFilters[name];
        if (!filters) return;

        // Aplicar cada filtro
        Object.entries(filters).forEach(([key, value]) => {
            const element = document.getElementById(key.replace(/([A-Z])/g, '-$1').toLowerCase());
            if (element) {
                if (element.type === 'checkbox') {
                    element.checked = value;
                } else {
                    element.value = value;
                }
            }
        });

        this.updateActiveFilterTags();
        
        // Fechar modal
        const modal = document.querySelector('.modal.show');
        if (modal) {
            const bsModal = bootstrap.Modal.getInstance(modal);
            bsModal.hide();
        }
    }

    deleteSavedFilter(name) {
        if (confirm(`Deseja excluir o filtro "${name}"?`)) {
            delete this.savedFilters[name];
            localStorage.setItem('savedFilters', JSON.stringify(this.savedFilters));
            
            // Recarregar modal
            const modal = document.querySelector('.modal.show');
            if (modal) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                bsModal.hide();
                setTimeout(() => this.showSavedFilters(), 300);
            }
        }
    }

    clearAllFilters() {
        if (confirm('Deseja limpar todos os filtros e configurações salvas?')) {
            this.resetFilters();
            this.savedFilters = {};
            this.searchHistory = [];
            localStorage.removeItem('savedFilters');
            localStorage.removeItem('searchHistory');
            this.updateSearchHistoryDropdown();
        }
    }

    setupSearchHistory() {
        this.updateSearchHistoryDropdown();
    }

    updateSearchHistoryDropdown() {
        const dropdown = document.getElementById('search-history-dropdown');
        if (!dropdown) return;

        const historyItems = this.searchHistory.slice(0, 5); // Mostrar apenas 5
        
        dropdown.innerHTML = '<li><h6 class="dropdown-header">Buscas Recentes</h6></li>';
        
        if (historyItems.length === 0) {
            dropdown.innerHTML += '<li><span class="dropdown-item-text text-muted">Nenhuma busca recente</span></li>';
        } else {
            historyItems.forEach(item => {
                dropdown.innerHTML += `
                    <li>
                        <a class="dropdown-item" href="#" onclick="advancedSearch.useHistoryItem('${item}')">
                            <i class="bi bi-clock-history me-2"></i>${item}
                        </a>
                    </li>
                `;
            });
            
            dropdown.innerHTML += `
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="#" onclick="advancedSearch.clearSearchHistory()">
                        <i class="bi bi-trash me-2"></i>Limpar histórico
                    </a>
                </li>
            `;
        }
    }

    useHistoryItem(item) {
        document.getElementById('smart-search').value = item;
        this.handleSmartSearch(item);
    }

    clearSearchHistory() {
        this.searchHistory = [];
        localStorage.removeItem('searchHistory');
        this.updateSearchHistoryDropdown();
    }

    loadSavedFilters() {
        // Carregar filtros da URL se existirem
        const params = new URLSearchParams(window.location.search);
        
        params.forEach((value, key) => {
            const element = document.getElementById(key.replace(/([A-Z])/g, '-$1').toLowerCase());
            if (element) {
                if (element.type === 'checkbox') {
                    element.checked = value === 'true';
                } else {
                    element.value = value;
                }
            }
        });

        this.updateActiveFilterTags();
    }
}

// Função debounce para performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Inicializar quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    // Verificar se estamos na página de viagens
    if (window.location.pathname.includes('/viagens')) {
        window.advancedSearch = new AdvancedSearchManager();
    }
});

// Exportar para uso global
window.AdvancedSearchManager = AdvancedSearchManager;

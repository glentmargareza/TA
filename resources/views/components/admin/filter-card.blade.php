@props([
    'action' => '',
    'method' => 'GET',
    'resetUrl' => null,
    'hasReset' => false,
    'grid' => true,
    'gridCols' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
    'actionCols' => 'flex items-end gap-2',
    'submitLabel' => 'Filter',
    'submitIcon' => null,
    'liveSearch' => true,
    'tableTarget' => null,
    'paginationTarget' => null,
])

@php
    $queryParams = request()->except(['page']);
    $isFiltered = $hasReset || ($resetUrl && count(array_filter($queryParams, fn($val) => $val !== null && $val !== '' && $val !== 'ALL' && $val !== 'all')) > 0);
    $isLiveSearch = filter_var($attributes->get('live-search', $liveSearch), FILTER_VALIDATE_BOOLEAN);
@endphp

<div {{ $attributes->except(['live-search', 'table-target', 'pagination-target'])->merge(['class' => 'bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 sm:p-5 mb-5 sm:mb-6 filter-card-web']) }} data-filter-card="true">
    <form method="{{ strtoupper($method) === 'POST' ? 'POST' : 'GET' }}"
          action="{{ $action }}"
          @if($isLiveSearch)
              data-live-filter="true"
              data-reset-url="{{ $resetUrl }}"
              @if($tableTarget) data-table-target="{{ $tableTarget }}" @endif
              @if($paginationTarget) data-pagination-target="{{ $paginationTarget }}" @endif
          @endif
          class="space-y-4">
        @if(strtoupper($method) === 'POST')
            @csrf
        @endif

        @if(isset($header))
            {{ $header }}
        @endif

        @if($grid)
            <div class="grid {{ $gridCols }} gap-3">
                {{ $slot }}

                @if(!isset($actions))
                <div class="{{ $actionCols }}">
                    <button type="submit" class="flex-1 py-2 sm:py-2.5 px-3.5 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold transition-all shadow-xs hover:shadow active:scale-95 cursor-pointer inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <x-icon :name="$submitIcon ?? 'search'" class="w-3.5 h-3.5 shrink-0" />
                        <span>{{ $submitLabel }}</span>
                    </button>
                    @if($resetUrl)
                        <a href="{{ $resetUrl }}"
                           data-role="clear-filter"
                           class="py-2 sm:py-2.5 px-3.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 hover:text-rose-600 text-xs font-bold transition-colors inline-flex items-center justify-center gap-1 whitespace-nowrap shadow-2xs {{ $isFiltered ? '' : 'hidden' }}"
                           title="Reset seluruh filter">
                            <span>Reset</span>
                        </a>
                    @endif
                </div>
                @else
                    {{ $actions }}
                @endif
            </div>
        @else
            <div class="flex flex-wrap items-center gap-3">
                {{ $slot }}

                @if(!isset($actions))
                <div class="flex items-center gap-2">
                    <button type="submit" class="py-2 sm:py-2.5 px-4 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold transition-all shadow-xs hover:shadow active:scale-95 cursor-pointer inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <x-icon :name="$submitIcon ?? 'search'" class="w-3.5 h-3.5 shrink-0" />
                        <span>{{ $submitLabel }}</span>
                    </button>
                    @if($resetUrl)
                        <a href="{{ $resetUrl }}"
                           data-role="clear-filter"
                           class="py-2 sm:py-2.5 px-3.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 hover:text-rose-600 text-xs font-bold transition-colors inline-flex items-center justify-center gap-1 whitespace-nowrap shadow-2xs {{ $isFiltered ? '' : 'hidden' }}"
                           title="Reset pencarian">
                            <span>Reset</span>
                        </a>
                    @endif
                </div>
                @else
                    {{ $actions }}
                @endif
            </div>
        @endif
    </form>
</div>

@if($isLiveSearch)
@once('card-filter-live-search')
<script>
(function() {
    'use strict';

    var debounceTimer = null;
    var abortController = null;

    function getFormQueryString(form) {
        var formData = new FormData(form);
        var params = new URLSearchParams();
        for (var pair of formData.entries()) {
            var key = pair[0];
            var val = pair[1];
            if (key === '_token' || key === '_method') continue;
            if (typeof val === 'string' && val.trim() !== '' && val.trim() !== 'ALL' && val.trim() !== 'all') {
                params.append(key, val.trim());
            }
        }
        return params.toString();
    }

    function updateClearButtonState(form) {
        var clearBtn = form.querySelector('[data-role="clear-filter"]');
        if (!clearBtn) return;
        var hasValue = false;
        var inputs = form.querySelectorAll('input:not([type="hidden"]), select, textarea');
        inputs.forEach(function(input) {
            if (input.type === 'checkbox' || input.type === 'radio') {
                if (input.checked) hasValue = true;
            } else {
                var val = (input.value || '').trim();
                if (val !== '' && val !== 'ALL' && val !== 'all') {
                    hasValue = true;
                }
            }
        });
        if (hasValue) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
    }

    function resolveTableContainer(form, rootDoc) {
        var root = rootDoc || document;
        var customSel = form ? form.dataset.tableTarget : null;
        if (customSel && customSel.trim() !== '') {
            var el = root.querySelector(customSel);
            if (el) return el;
        }

        // 1. Element with explicit data-table-container or class table-custom-container
        var explicit = root.querySelector('[data-table-container], .table-custom-container');
        if (explicit) return explicit;

        // 2. Element sibling following the filter card
        var card = form ? (form.closest('[data-filter-card], .filter-card-web') || form.parentElement) : null;
        if (card) {
            var next = card.nextElementSibling;
            while (next) {
                if (next.querySelector('table') || next.tagName === 'TABLE') {
                    return next;
                }
                next = next.nextElementSibling;
            }
        }

        // 3. Fallback: find any table and its outer card
        var anyTable = root.querySelector('main table, #print-area table, table');
        if (anyTable) {
            return anyTable.closest('.rounded-2xl, .rounded-3xl, .shadow-xs, .shadow-2xs, .overflow-hidden') || anyTable.parentElement;
        }

        return null;
    }

    function resolvePaginationContainer(form, rootDoc, tableContainer) {
        var root = rootDoc || document;
        var customSel = form ? form.dataset.paginationTarget : null;
        if (customSel && customSel.trim() !== '') {
            var el = root.querySelector(customSel);
            if (el) return el;
        }

        var explicit = root.querySelector('[data-pagination-container], .pagination-container');
        if (explicit) return explicit;

        if (tableContainer && tableContainer.nextElementSibling) {
            var next = tableContainer.nextElementSibling;
            if (next.matches && (next.matches('.pagination-container, nav[role="navigation"], nav[aria-label]') || next.querySelector('nav[role="navigation"], .pagination'))) {
                return next;
            }
        }

        var nav = root.querySelector('nav[role="navigation"], .pagination');
        if (nav) return nav.parentElement;

        return null;
    }

    function fetchFilteredData(form, targetUrl, pushState) {
        if (abortController) {
            abortController.abort();
        }
        abortController = new AbortController();

        var tableContainer = resolveTableContainer(form, document);
        var pagContainer = resolvePaginationContainer(form, document, tableContainer);

        if (tableContainer) {
            tableContainer.classList.add('opacity-50', 'pointer-events-none', 'transition-opacity', 'duration-150');
        }

        fetch(targetUrl, {
            signal: abortController.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.text();
        })
        .then(function(html) {
            var parser = new DOMParser();
            var doc = parser.parseFromString(html, 'text/html');

            var newTable = resolveTableContainer(form, doc);
            var newPag = resolvePaginationContainer(form, doc, newTable);

            // 1. Swap Table Container
            if (newTable && tableContainer) {
                tableContainer.innerHTML = newTable.innerHTML;
            }

            // 2. Swap Pagination if separate
            if (pagContainer && newPag && pagContainer !== tableContainer && !tableContainer.contains(pagContainer)) {
                if (newPag.innerHTML.trim() !== '') {
                    pagContainer.innerHTML = newPag.innerHTML;
                    pagContainer.classList.remove('hidden');
                } else {
                    pagContainer.innerHTML = '';
                    pagContainer.classList.add('hidden');
                }
            }

            // 3. Sync Summary Badges and Filter Actions Bar if present
            var summarySelectors = ['[data-summary-badge]', '.filter-summary-badge', '[data-filter-summary]'];
            summarySelectors.forEach(function(sel) {
                var oldEls = document.querySelectorAll(sel);
                var newEls = doc.querySelectorAll(sel);
                if (oldEls.length && newEls.length) {
                    oldEls.forEach(function(el, i) {
                        if (newEls[i]) el.innerHTML = newEls[i].innerHTML;
                    });
                }
            });

            var oldBar = form.querySelector('.filter-actions-bar');
            var newBar = doc.querySelector('.filter-actions-bar');
            if (oldBar && newBar) {
                oldBar.innerHTML = newBar.innerHTML;
            }

            // 4. Update History URL
            if (pushState !== false) {
                window.history.replaceState({ liveSearchUrl: targetUrl }, '', targetUrl);
            }

            // 5. Re-bind / notify
            if (typeof window.setupConfirmInterceptors === 'function') {
                window.setupConfirmInterceptors();
            }
            document.dispatchEvent(new CustomEvent('live-filter:updated', { detail: { url: targetUrl } }));
        })
        .catch(function(err) {
            if (err.name !== 'AbortError') {
                console.error('Live filter error:', err);
            }
        })
        .finally(function() {
            if (tableContainer) {
                tableContainer.classList.remove('opacity-50', 'pointer-events-none');
            }
        });
    }

    function triggerSearch(form, immediate) {
        clearTimeout(debounceTimer);
        updateClearButtonState(form);

        var action = form.getAttribute('action') || window.location.pathname;
        var qs = getFormQueryString(form);
        var url = action + (qs ? ('?' + qs) : '');

        if (immediate) {
            fetchFilteredData(form, url, true);
        } else {
            debounceTimer = setTimeout(function() {
                fetchFilteredData(form, url, true);
            }, 300);
        }
    }

    // Input event (debounced for text search)
    document.addEventListener('input', function(e) {
        var form = e.target.closest('form[data-live-filter="true"]');
        if (!form) return;
        triggerSearch(form, false);
    });

    // Change event (immediate for select, date, radio, checkbox)
    document.addEventListener('change', function(e) {
        var form = e.target.closest('form[data-live-filter="true"]');
        if (!form) return;
        if (e.target.tagName === 'SELECT' || e.target.type === 'checkbox' || e.target.type === 'radio' || e.target.type === 'date') {
            triggerSearch(form, true);
        }
    });

    // Submit event (prevent full page reload)
    document.addEventListener('submit', function(e) {
        var form = e.target.closest('form[data-live-filter="true"]');
        if (!form) return;
        e.preventDefault();
        triggerSearch(form, true);
    });

    // Clear / Reset button click
    document.addEventListener('click', function(e) {
        var clearBtn = e.target.closest('form[data-live-filter="true"] [data-role="clear-filter"]');
        if (!clearBtn) return;
        var form = clearBtn.closest('form[data-live-filter="true"]');
        if (!form) return;

        e.preventDefault();
        form.querySelectorAll('input:not([type="hidden"]), textarea').forEach(function(el) {
            el.value = '';
        });
        form.querySelectorAll('select').forEach(function(el) {
            el.selectedIndex = 0;
        });
        form.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(function(el) {
            el.checked = false;
        });
        var defaultRadio = form.querySelector('input[type="radio"][value=""]');
        if (defaultRadio) {
            defaultRadio.checked = true;
        }

        updateClearButtonState(form);
        var resetUrl = form.dataset.resetUrl || form.getAttribute('action') || window.location.pathname;
        fetchFilteredData(form, resetUrl, true);
    });

    // Pagination link clicks (smooth AJAX paging)
    document.addEventListener('click', function(e) {
        var pageLink = e.target.closest('nav[role="navigation"] a, .pagination a, nav[aria-label] a, .pagination-container a');
        if (!pageLink || !pageLink.href) return;
        if (pageLink.origin !== window.location.origin) return;

        var form = document.querySelector('form[data-live-filter="true"]');
        if (!form) return;

        e.preventDefault();
        fetchFilteredData(form, pageLink.href, true);
    });

    // Browser back / forward buttons
    window.addEventListener('popstate', function() {
        var form = document.querySelector('form[data-live-filter="true"]');
        if (!form) return;
        fetchFilteredData(form, window.location.href, false);
    });
})();
</script>
@endonce
@endif

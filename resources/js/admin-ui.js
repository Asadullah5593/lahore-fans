/**
 * Admin loading feedback.
 *
 * Saving an outlet writes to the database and re-renders a list of a hundred-odd
 * rows, so there is a real gap between the click and the next paint. Without a
 * signal people click Save twice. This adds three things, all progressive — the
 * pages work exactly as before if the script never runs:
 *
 *   1. A thin progress bar across the top on any navigation or submit.
 *   2. Submit buttons turn into a spinner and lock, so nothing is sent twice.
 *   3. Filter <select>s submit themselves on change and grey out while loading.
 */

const BAR_ID = 'lf-progress';

function progressBar() {
    let bar = document.getElementById(BAR_ID);
    if (bar) return bar;

    bar = document.createElement('div');
    bar.id = BAR_ID;
    bar.className = 'lf-progress';
    bar.innerHTML = '<span class="lf-progress__fill"></span>';
    document.body.appendChild(bar);
    return bar;
}

function startProgress() {
    const bar = progressBar();
    bar.classList.remove('is-done');
    // Force a reflow so re-triggering restarts the animation rather than
    // continuing the previous one.
    void bar.offsetWidth;
    bar.classList.add('is-active');
}

function stopProgress() {
    const bar = document.getElementById(BAR_ID);
    if (!bar) return;
    bar.classList.remove('is-active');
    bar.classList.add('is-done');
}

/** Swap a submit button for a spinner and stop it being pressed again. */
function lockButton(button) {
    if (!button || button.dataset.lfLocked) return;

    button.dataset.lfLocked = '1';
    button.dataset.lfLabel = button.innerHTML;
    button.disabled = true;
    button.classList.add('is-loading');

    // Icon-only controls (the star toggle) just spin in place; buttons with a
    // label keep their text so the width does not jump.
    const label = button.textContent.trim();
    button.innerHTML = label
        ? `<span class="lf-spinner" aria-hidden="true"></span><span>${label}</span>`
        : '<span class="lf-spinner" aria-hidden="true"></span>';
}

/**
 * Turn a long <select> into a combobox with a search box.
 *
 * The original <select> stays in the DOM and keeps holding the value — it is
 * simply hidden. Everything still submits normally if this never runs, and the
 * existing data-auto-submit handler fires because we dispatch a real change
 * event rather than calling submit() ourselves.
 */
function makeSearchable(select) {
    const options = Array.from(select.options).map((o) => ({ value: o.value, label: o.text }));
    const labelFor = (value) => options.find((o) => o.value === value)?.label ?? options[0]?.label ?? '';

    const wrap = document.createElement('div');
    wrap.className = 'lf-combo';
    select.parentNode.insertBefore(wrap, select);
    wrap.appendChild(select);
    select.classList.add('lf-combo__native');

    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'lf-combo__toggle';
    toggle.setAttribute('aria-haspopup', 'listbox');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.textContent = labelFor(select.value);

    const panel = document.createElement('div');
    panel.className = 'lf-combo__panel';
    panel.hidden = true;

    const search = document.createElement('input');
    search.type = 'search';
    search.className = 'lf-combo__search';
    search.placeholder = select.dataset.searchPlaceholder || 'Search…';
    search.setAttribute('aria-label', search.placeholder);

    const list = document.createElement('ul');
    list.className = 'lf-combo__list';
    list.setAttribute('role', 'listbox');

    panel.append(search, list);
    wrap.append(toggle, panel);

    let active = -1;
    let shown = options;

    function render() {
        const term = search.value.trim().toLowerCase();
        shown = term ? options.filter((o) => o.label.toLowerCase().includes(term)) : options;
        list.innerHTML = '';

        if (!shown.length) {
            const empty = document.createElement('li');
            empty.className = 'lf-combo__empty';
            empty.textContent = 'No match';
            list.appendChild(empty);
            return;
        }

        shown.forEach((option, i) => {
            const item = document.createElement('li');
            item.className = 'lf-combo__option';
            item.textContent = option.label;
            item.setAttribute('role', 'option');
            item.dataset.value = option.value;
            if (option.value === select.value) item.classList.add('is-selected');
            if (i === active) item.classList.add('is-active');
            item.addEventListener('mousedown', (e) => {
                e.preventDefault();
                choose(option.value);
            });
            list.appendChild(item);
        });
    }

    function choose(value) {
        select.value = value;
        toggle.textContent = labelFor(value);
        close();
        select.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function open() {
        panel.hidden = false;
        toggle.setAttribute('aria-expanded', 'true');
        search.value = '';
        active = -1;
        render();
        search.focus();
    }

    function close() {
        panel.hidden = true;
        toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', () => (panel.hidden ? open() : close()));
    search.addEventListener('input', () => {
        active = -1;
        render();
    });

    search.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            close();
            toggle.focus();
        } else if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            event.preventDefault();
            if (!shown.length) return;
            active = event.key === 'ArrowDown'
                ? Math.min(active + 1, shown.length - 1)
                : Math.max(active - 1, 0);
            render();
            list.querySelector('.is-active')?.scrollIntoView({ block: 'nearest' });
        } else if (event.key === 'Enter') {
            event.preventDefault();
            if (shown[active]) choose(shown[active].value);
            else if (shown.length === 1) choose(shown[0].value);
        }
    });

    document.addEventListener('click', (event) => {
        if (!wrap.contains(event.target)) close();
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // --- Form submits -----------------------------------------------------
    document.addEventListener(
        'submit',
        (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) return;

            // A cancelled confirm() dialog prevents the default before we get
            // here — never show a loader for something that is not happening.
            if (event.defaultPrevented) return;

            startProgress();
            lockButton(form.querySelector('button[type="submit"], button:not([type])'));
            form.classList.add('is-loading');
        },
        // Listen in the bubble phase so inline onsubmit handlers run first.
        false
    );

    // --- Navigation -------------------------------------------------------
    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[href]');
        if (!link) return;

        const href = link.getAttribute('href');
        const external = link.target === '_blank' || link.hasAttribute('download');

        if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || external) {
            return;
        }
        if (link.origin && link.origin !== window.location.origin) return;

        startProgress();
    });

    // --- Filter selects ---------------------------------------------------
    document.querySelectorAll('[data-auto-submit]').forEach((field) => {
        field.addEventListener('change', () => field.form?.requestSubmit());
    });

    document.querySelectorAll('select[data-searchable]').forEach(makeSearchable);

    // Restoring a page from the back/forward cache leaves the bar mid-flight.
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            stopProgress();
            document.querySelectorAll('button[data-lf-locked]').forEach((button) => {
                button.disabled = false;
                button.classList.remove('is-loading');
                button.innerHTML = button.dataset.lfLabel ?? button.innerHTML;
                delete button.dataset.lfLocked;
            });
        }
    });
});

window.addEventListener('load', stopProgress);

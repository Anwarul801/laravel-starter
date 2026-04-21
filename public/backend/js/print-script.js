function headerHide(btn) {
  const headers = document.querySelectorAll('.header');
    headers.forEach(header => {
        // Check current state
        const isHidden = header.style.opacity === '0';

        if (!isHidden) {
            header.style.opacity = '0';
            header.style.height = 'auto';
            btn.innerText = "With Header";
        } else {
            header.style.opacity = '1';
            header.style.height = 'auto';
            btn.innerText = "Without Header";
        }
    })
}


// student Lists
/**
 * Student Table Column Toggle + LocalStorage
 * Fully isolated — no global variable conflicts.
 */

(function (window, document) {

    const STORAGE_KEY = "studentColumnState";
    const table = document.getElementById('studentTable');
    const checkboxes = document.querySelectorAll('.col-toggles input[type="checkbox"]');
    const resetBtn = document.getElementById('resetBtn');
    const printBtn = document.getElementById('printBtn');

    if (!table || !checkboxes) {
        console.warn("Student column script: Required elements not found.");
        return;
    }

    // Generate class name
    function hideClassFor(col) {
        return 'hide-' + col;
    }

    // Load saved column state
    function loadSavedState() {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (!saved) return;

        let state;
        try {
            state = JSON.parse(saved);
        } catch (e) {
            console.error("Invalid student column state in storage");
            return;
        }

        checkboxes.forEach(cb => {
            const col = cb.getAttribute('data-col');

            if (state[col] === false) {
                cb.checked = false;
                table.classList.add(hideClassFor(col));
            } else {
                cb.checked = true;
                table.classList.remove(hideClassFor(col));
            }
        });
    }

    // Save state
    function saveState() {
        const state = {};
        checkboxes.forEach(cb => {
            const col = cb.getAttribute('data-col');
            state[col] = cb.checked;
        });
        localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
    }

    // Toggle columns
    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const col = cb.getAttribute('data-col');
            const cls = hideClassFor(col);

            if (cb.checked) table.classList.remove(cls);
            else table.classList.add(cls);

            saveState();
        });
    });

    // Reset
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            checkboxes.forEach(cb => cb.checked = true);
            checkboxes.forEach(cb => {
                table.classList.remove(hideClassFor(cb.getAttribute('data-col')));
            });
            saveState();
        });
    }

    // Print
    if (printBtn) {
        printBtn.addEventListener('click', () => window.print());
    }

    // Init
    document.addEventListener("DOMContentLoaded", loadSavedState);

})(window, document);

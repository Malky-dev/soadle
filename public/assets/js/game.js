// -----------------------------------------------------------------------------
// DOM references
// -----------------------------------------------------------------------------

const characterSearch = document.getElementById('character_search');
const guessIdInput = document.getElementById('guess_id');
const resultsList = document.getElementById('character_search_results');
const emptyMessage = document.getElementById('character_search_empty');

if (
    characterSearch !== null
    && guessIdInput !== null
    && resultsList !== null
    && emptyMessage !== null
) {
    const resultItems = Array.from(
        resultsList.querySelectorAll('.search-result')
    );

    // -------------------------------------------------------------------------
    // Search state helpers
    // -------------------------------------------------------------------------

    const getSearchValue = () => characterSearch.value.trim().toLocaleLowerCase();

    const getVisibleResults = () => {
        return resultItems.filter((item) => item.hidden === false);
    };

    const hasSelectedCharacter = () => guessIdInput.value !== '';

    const getCharacterId = (item) => item.dataset.characterId ?? '';

    const getCharacterName = (item) => item.dataset.characterName ?? '';

    const getForm = () => characterSearch.closest('form');

    // -------------------------------------------------------------------------
    // UI visibility helpers
    // -------------------------------------------------------------------------

    const hideResult = (item) => {
        item.hidden = true;
    };

    const showResult = (item) => {
        item.hidden = false;
    };

    const hideEmptyMessage = () => {
        emptyMessage.hidden = true;
    };

    const showEmptyMessage = () => {
        emptyMessage.hidden = false;
    };

    const hideAllResults = () => {
        for (const item of resultItems) {
            hideResult(item);
        }

        hideEmptyMessage();
    };

    // -------------------------------------------------------------------------
    // Search filtering
    // -------------------------------------------------------------------------

    const matchesSearch = (item, search) => {
        return getCharacterName(item).includes(search);
    };

    const updateEmptyMessage = () => {
        const shouldShowEmptyMessage =
            getSearchValue() !== ''
            && getVisibleResults().length === 0;

        if (shouldShowEmptyMessage) {
            showEmptyMessage();
            return;
        }

        hideEmptyMessage();
    };

    const updateResultsVisibility = () => {
        const search = getSearchValue();

        if (search === '') {
            hideAllResults();
            return;
        }

        for (const item of resultItems) {
            if (matchesSearch(item, search)) {
                showResult(item);
                continue;
            }

            hideResult(item);
        }

        updateEmptyMessage();
    };

    // -------------------------------------------------------------------------
    // Character selection
    // -------------------------------------------------------------------------

    const clearSelection = () => {
        guessIdInput.value = '';
    };

    const selectCharacter = (item, submit = false) => {
        const characterId = getCharacterId(item);

        if (characterId === '') {
            return;
        }

        // The visible input is only used for search.
        // The hidden id is the value submitted to the server.
        guessIdInput.value = characterId;
        characterSearch.value = item.textContent?.trim() ?? '';

        hideAllResults();

        if (submit) {
            submitForm();
        }
    };

    const selectFirstVisibleResult = (submit = false) => {
        const firstVisibleResult = getVisibleResults()[0];

        if (firstVisibleResult === undefined) {
            return;
        }

        selectCharacter(firstVisibleResult, submit);
    };

    // -------------------------------------------------------------------------
    // Form submission
    // -------------------------------------------------------------------------

    const submitForm = () => {
        const form = getForm();

        if (form instanceof HTMLFormElement) {
            form.requestSubmit();
        }
    };

    // -------------------------------------------------------------------------
    // Event handlers
    // -------------------------------------------------------------------------

    const handleSearchInput = () => {
        // Editing the search field invalidates any previous selection.
        clearSelection();
        updateResultsVisibility();
    };

    const handleSearchEnter = (event) => {
        if (event.key !== 'Enter') {
            return;
        }

        event.preventDefault();

        if (!hasSelectedCharacter()) {
            selectFirstVisibleResult(true);
            return;
        }

        submitForm();
    };

    const handleResultClick = (event) => {
        const target = event.target;

        if (!(target instanceof HTMLElement)) {
            return;
        }

        const item = target.closest('.search-result');

        if (item === null) {
            return;
        }

        selectCharacter(item, true);
    };

    const handleDocumentClick = (event) => {
        const target = event.target;

        if (!(target instanceof HTMLElement)) {
            return;
        }

        // Keep the dropdown open while interacting with the search component.
        if (
            target === characterSearch
            || resultsList.contains(target)
        ) {
            return;
        }

        hideAllResults();
    };

    // -------------------------------------------------------------------------
    // Event bindings
    // -------------------------------------------------------------------------

    characterSearch.addEventListener('input', handleSearchInput);
    characterSearch.addEventListener('keydown', handleSearchEnter);
    resultsList.addEventListener('click', handleResultClick);
    document.addEventListener('click', handleDocumentClick);
}
export function searchableSelect(config = {}) {
    return {
        open: false,
        search: '',
        value: config.value !== null && config.value !== undefined ? String(config.value) : '',
        options: Array.isArray(config.options) ? config.options : [],
        placeholder: config.placeholder || 'Select…',
        emptyText: config.emptyText || 'No results',
        highlightIndex: -1,

        get selectedLabel() {
            if (this.value === '') {
                return this.placeholder;
            }

            const match = this.options.find((option) => String(option.value) === String(this.value));

            return match?.label ?? this.placeholder;
        },

        get filteredOptions() {
            const query = this.search.trim().toLowerCase();

            if (!query) {
                return this.options;
            }

            return this.options.filter((option) => String(option.label).toLowerCase().includes(query));
        },

        toggle() {
            if (this.open) {
                this.close();
                return;
            }

            this.open = true;
            this.search = '';
            this.highlightIndex = this.filteredOptions.findIndex(
                (option) => String(option.value) === String(this.value)
            );

            this.$nextTick(() => {
                this.$refs.searchInput?.focus();
            });
        },

        close() {
            this.open = false;
            this.search = '';
            this.highlightIndex = -1;
        },

        select(option) {
            this.value = String(option.value);
            this.close();
        },

        clear(event) {
            event?.stopPropagation();
            this.value = '';
            this.close();
        },

        onSearchInput() {
            this.highlightIndex = this.filteredOptions.length ? 0 : -1;
        },

        onKeydown(event) {
            if (!this.open) {
                if (event.key === 'ArrowDown' || event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    this.toggle();
                }
                return;
            }

            const options = this.filteredOptions;

            if (event.key === 'Escape') {
                event.preventDefault();
                this.close();
                return;
            }

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                if (!options.length) {
                    return;
                }
                this.highlightIndex = (this.highlightIndex + 1) % options.length;
                return;
            }

            if (event.key === 'ArrowUp') {
                event.preventDefault();
                if (!options.length) {
                    return;
                }
                this.highlightIndex = this.highlightIndex <= 0
                    ? options.length - 1
                    : this.highlightIndex - 1;
                return;
            }

            if (event.key === 'Enter') {
                event.preventDefault();
                if (this.highlightIndex >= 0 && options[this.highlightIndex]) {
                    this.select(options[this.highlightIndex]);
                }
            }
        },
    };
}

export function registerDialogStore(Alpine) {
    Alpine.store('dialog', {
        open: false,
        type: 'alert',
        variant: 'info',
        title: '',
        message: '',
        confirmLabel: 'OK',
        cancelLabel: 'Cancel',
        _resolve: null,

        alert(options = {}) {
            return this._open({
                type: 'alert',
                variant: options.variant ?? 'info',
                title: options.title ?? 'Notice',
                message: options.message ?? '',
                confirmLabel: options.confirmLabel ?? 'OK',
                cancelLabel: options.cancelLabel ?? 'Cancel',
            });
        },

        confirm(options = {}) {
            return this._open({
                type: 'confirm',
                variant: options.variant ?? 'warning',
                title: options.title ?? 'Please confirm',
                message: options.message ?? 'Are you sure you want to continue?',
                confirmLabel: options.confirmLabel ?? 'Confirm',
                cancelLabel: options.cancelLabel ?? 'Cancel',
            });
        },

        _open(config) {
            return new Promise((resolve) => {
                this.type = config.type;
                this.variant = config.variant;
                this.title = config.title;
                this.message = config.message;
                this.confirmLabel = config.confirmLabel;
                this.cancelLabel = config.cancelLabel;
                this._resolve = resolve;
                this.open = true;
            });
        },

        accept() {
            if (this._resolve) {
                this._resolve(true);
            }
            this._reset();
        },

        dismiss() {
            if (this._resolve) {
                this._resolve(this.type === 'alert');
            }
            this._reset();
        },

        _reset() {
            this.open = false;
            this._resolve = null;
        },
    });

    Alpine.data('confirmSubmit', (options = {}) => ({
        message: typeof options === 'string' ? options : (options.message ?? 'Are you sure?'),
        title: options.title ?? 'Please confirm',
        variant: options.variant ?? 'warning',
        confirmLabel: options.confirmLabel ?? 'Confirm',
        cancelLabel: options.cancelLabel ?? 'Cancel',

        async ask(event) {
            event.preventDefault();
            const ok = await Alpine.store('dialog').confirm({
                title: this.title,
                message: this.message,
                variant: this.variant,
                confirmLabel: this.confirmLabel,
                cancelLabel: this.cancelLabel,
            });

            if (ok) {
                event.target.submit();
            }
        },
    }));

    document.addEventListener('submit', async (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        const message = form.dataset.confirm;
        if (!message || form.dataset.confirming === '1') {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        const ok = await Alpine.store('dialog').confirm({
            title: form.dataset.confirmTitle ?? 'Please confirm',
            message,
            variant: form.dataset.confirmVariant ?? 'warning',
            confirmLabel: form.dataset.confirmLabel ?? 'Confirm',
            cancelLabel: form.dataset.cancelLabel ?? 'Cancel',
        });

        if (!ok) {
            return;
        }

        form.dataset.confirming = '1';
        form.requestSubmit();
        delete form.dataset.confirming;
    }, true);
}

export function bssAlert(message, options = {}) {
    return window.Alpine.store('dialog').alert({
        message,
        ...options,
    });
}

export function bssConfirm(message, options = {}) {
    return window.Alpine.store('dialog').confirm({
        message,
        ...options,
    });
}

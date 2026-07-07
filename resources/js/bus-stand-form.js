export function busStandForm(config) {
    return {
        terminalsMap: config.terminalsMap || {},
        usersByTerminal: config.usersByTerminal || {},
        terminalId: String(config.terminalId || ''),
        fromCity: config.fromCity || '',
        toCity: config.toCity || '',
        customLabel: config.customLabel || false,
        standName: config.standName || '',
        selectedUserIds: config.selectedUserIds || [],
        cities: config.cities || [],
        routes: config.initialRoutes || [
            { destination_city: '', base_fare: '', distance_km: '', duration_minutes: '' },
        ],

        get routeLabel() {
            if (this.customLabel) {
                return this.standName;
            }
            if (this.fromCity && this.toCity) {
                return `${this.fromCity} → ${this.toCity}`;
            }

            return '';
        },

        get terminalUsers() {
            return this.usersByTerminal[this.terminalId] || [];
        },

        init() {
            this.syncToSelect();
            this.filterToOptions();
            if (! this.customLabel && ! this.standName) {
                this.standName = this.routeLabel;
            }
            this.$watch('routeLabel', (value) => {
                if (! this.customLabel) {
                    this.standName = value;
                }
            });
        },

        onTerminalChange(id) {
            this.terminalId = String(id);
            this.fromCity = this.terminalsMap[id] || '';
            this.selectedUserIds = [];
            this.filterToOptions();
            this.routes.forEach((route) => {
                if (route.destination_city === this.fromCity) {
                    route.destination_city = '';
                }
            });
            const toSelect = document.getElementById('to_city');
            if (toSelect?.value === this.fromCity) {
                toSelect.value = '';
                this.toCity = '';
            }
        },

        addRoute() {
            this.routes.push({
                destination_city: '',
                base_fare: '',
                distance_km: '',
                duration_minutes: '',
            });
        },

        removeRoute(index) {
            if (this.routes.length > 1) {
                this.routes.splice(index, 1);
            }
        },

        onToChange(value) {
            if (value === this.fromCity) {
                this.toCity = '';
                const toSelect = document.getElementById('to_city');
                if (toSelect) {
                    toSelect.value = '';
                }

                return;
            }
            this.toCity = value;
        },

        syncToSelect() {
            const toSelect = document.getElementById('to_city');
            if (toSelect) {
                this.toCity = toSelect.value || this.toCity;
                toSelect.addEventListener('change', (e) => this.onToChange(e.target.value));
            }
        },

        filterToOptions() {
            const toSelect = document.getElementById('to_city');
            if (! toSelect) {
                return;
            }
            [...toSelect.options].forEach((opt) => {
                if (! opt.value) {
                    return;
                }
                const disabled = opt.value === this.fromCity;
                opt.disabled = disabled;
                if (disabled && opt.selected) {
                    toSelect.value = '';
                    this.toCity = '';
                }
            });
        },

    };
}

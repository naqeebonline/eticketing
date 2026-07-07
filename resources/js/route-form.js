export function routeForm(config) {
    return {
        departureCity: config.departureCity || '',
        destinationCity: config.destinationCity || '',

        get routeName() {
            if (!this.departureCity || !this.destinationCity) {
                return '';
            }

            return `${this.departureCity} → ${this.destinationCity}`;
        },

        init() {
            this.bindCitySelect('departure_city', (value) => {
                this.departureCity = value;
            });
            this.bindCitySelect('destination_city', (value) => {
                this.destinationCity = value;
            });
        },

        bindCitySelect(id, setter) {
            const select = document.getElementById(id);
            if (!select) {
                return;
            }

            setter(select.value || '');
            select.addEventListener('change', (e) => setter(e.target.value));
        },
    };
}

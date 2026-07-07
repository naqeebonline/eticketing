export const WEEKDAY_ROWS = [
    { value: 1, label: 'Monday' },
    { value: 2, label: 'Tuesday' },
    { value: 3, label: 'Wednesday' },
    { value: 4, label: 'Thursday' },
    { value: 5, label: 'Friday' },
    { value: 6, label: 'Saturday' },
    { value: 7, label: 'Sunday' },
];

export function scheduleForm(config) {
    return {
        routes: config.routes || [],
        vehicles: config.vehicles || [],
        drivers: config.drivers || [],
        routeId: String(config.routeId || ''),
        vehicleId: String(config.vehicleId || ''),
        driverId: config.driverId !== null && config.driverId !== undefined ? String(config.driverId) : '',
        initialStandId: config.standId ?? null,
        fare: config.fare ?? '',
        weekdays: config.initialWeekdays || {},
        weekdayRows: WEEKDAY_ROWS,
        departureDate: config.departureDate || '',
        departureTime: config.departureTime || '',
        arrivalTime: config.arrivalTime || '',

        get selectedRoute() {
            return this.routes.find((r) => String(r.id) === String(this.routeId));
        },

        get standId() {
            return this.selectedRoute?.bus_stand_id ?? this.initialStandId ?? null;
        },

        get filteredVehicles() {
            if (!this.standId) {
                return [];
            }

            return this.vehicles.filter((v) => String(v.bus_stand_id) === String(this.standId));
        },

        get filteredDrivers() {
            if (!this.standId) {
                return [];
            }

            return this.drivers.filter((d) => String(d.bus_stand_id) === String(this.standId));
        },

        get activeDayCount() {
            return WEEKDAY_ROWS.filter((day) => this.weekdays[day.value]?.departure_time).length;
        },

        get schedulePreview() {
            if (this.activeDayCount === 0) {
                return 'Jis din bus chalni hai us par time daalein — baqi khali chhor dein.';
            }

            const names = WEEKDAY_ROWS
                .filter((day) => this.weekdays[day.value]?.departure_time)
                .map((day) => `${day.label.slice(0, 3)} ${this.weekdays[day.value].departure_time}`)
                .join(' · ');

            return `${this.activeDayCount} din/week · har hafte repeat · agle saal tak trips auto banengi — ${names}`;
        },

        init() {
            WEEKDAY_ROWS.forEach((day) => {
                if (!this.weekdays[day.value]) {
                    this.weekdays[day.value] = { departure_time: '', arrival_time: '' };
                }
            });

            const initialRouteId = this.routeId;
            const initialVehicleId = this.vehicleId;
            const initialDriverId = this.driverId;

            if (initialRouteId) {
                this.onRouteChange(initialRouteId, false);
            }

            // Re-apply after x-for options render (edit form prefill)
            this.$nextTick(() => {
                if (initialVehicleId) {
                    this.vehicleId = String(initialVehicleId);
                }
                if (initialDriverId) {
                    this.driverId = String(initialDriverId);
                } else if (this.vehicleId) {
                    this.applyVehicleDefaultDriver();
                }
            });

            if (this.departureTime) {
                this.syncArrivalTime();
            }
        },

        onRouteChange(id, resetSelections = true) {
            this.routeId = String(id);

            if (resetSelections) {
                this.vehicleId = '';
                this.driverId = '';
            }

            const route = this.selectedRoute;
            if (route?.base_fare && resetSelections) {
                this.fare = route.base_fare;
            }

            WEEKDAY_ROWS.forEach((day) => this.syncArrivalForDay(day.value));
        },

        onVehicleChange(id) {
            this.vehicleId = String(id);
            this.applyVehicleDefaultDriver();
        },

        applyVehicleDefaultDriver() {
            const vehicle = this.filteredVehicles.find((v) => String(v.id) === String(this.vehicleId));
            if (vehicle?.driver_id) {
                this.driverId = String(vehicle.driver_id);
            }
        },

        onDayTimeChange(dayOfWeek) {
            this.syncArrivalForDay(dayOfWeek);
        },

        clearDay(dayOfWeek) {
            this.weekdays[dayOfWeek] = { departure_time: '', arrival_time: '' };
        },

        syncArrivalForDay(dayOfWeek) {
            const route = this.selectedRoute;
            const slot = this.weekdays[dayOfWeek];
            const duration = route?.duration_minutes;

            if (!duration || !slot?.departure_time) {
                return;
            }

            const [hours, minutes] = slot.departure_time.split(':').map(Number);
            if (Number.isNaN(hours) || Number.isNaN(minutes)) {
                return;
            }

            const totalMinutes = hours * 60 + minutes + Number(duration);
            const arrivalHours = Math.floor(totalMinutes / 60) % 24;
            const arrivalMinutes = totalMinutes % 60;

            slot.arrival_time = `${String(arrivalHours).padStart(2, '0')}:${String(arrivalMinutes).padStart(2, '0')}`;
        },

        onDepartureTimeChange() {
            this.syncArrivalTime();
        },

        syncArrivalTime() {
            const route = this.selectedRoute;
            const duration = route?.duration_minutes;

            if (!duration || !this.departureTime) {
                return;
            }

            const [hours, minutes] = this.departureTime.split(':').map(Number);
            if (Number.isNaN(hours) || Number.isNaN(minutes)) {
                return;
            }

            const totalMinutes = hours * 60 + minutes + Number(duration);
            const arrivalHours = Math.floor(totalMinutes / 60) % 24;
            const arrivalMinutes = totalMinutes % 60;

            this.arrivalTime = `${String(arrivalHours).padStart(2, '0')}:${String(arrivalMinutes).padStart(2, '0')}`;
        },
    };
}

export function scheduleDuplicateModal() {
    return {
        open: false,
        action: '',
        routeLabel: '',
        departureDate: '',
        departureTime: '',

        show(payload) {
            this.action = payload.action;
            this.routeLabel = payload.routeLabel;
            this.departureDate = payload.departureDate;
            this.departureTime = payload.departureTime;
            this.open = true;
        },

        close() {
            this.open = false;
        },
    };
}

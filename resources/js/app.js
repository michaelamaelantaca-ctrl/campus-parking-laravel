import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('parkingDashboard', () => ({
    search: '',
    filter: 'All',
    zoneId: '',
    slots: [],
    reservationModal: false,
    reservationSlotId: null,
    confirmModal: false,
    confirmAction: null,
    selectedZone: null,
    now: Date.now(),

    init() {
        this.pollLiveStatus();
        window.setInterval(() => {
            this.now = Date.now();
            this.pollLiveStatus();
        }, 1000);

        window.setInterval(() => {
            this.pollLiveStatus();
        }, 15000);
    },

    matchesLog(log) {
        const text = `${log.plate || ''} ${log.driver || ''}`.toLowerCase();
        const searchMatch = text.includes(this.search.toLowerCase());
        const filterMatch = this.filter === 'All'
            || (this.filter === 'Parked' && log.status === 'Checked-In')
            || (this.filter === 'Checked-Out' && log.status === 'Checked-Out')
            || (this.filter === 'Reserved' && log.status === 'Reserved');

        return searchMatch && filterMatch;
    },

    openReservation(slotId) {
        this.reservationSlotId = slotId;
        this.reservationModal = true;
    },

    closeReservation() {
        this.reservationModal = false;
        this.reservationSlotId = null;
    },

    requestConfirmation(action) {
        this.confirmAction = action;
        this.confirmModal = true;
    },

    runConfirmation() {
        if (this.confirmAction) {
            this.confirmAction();
        }

        this.confirmModal = false;
    },

    async loadSlots() {
        if (!this.zoneId) {
            this.slots = [];
            return;
        }

        try {
            const url = document.querySelector('[data-zone-slot-url]')?.dataset.zoneSlotUrl;
            if (!url) return;

            const response = await fetch(url);
            const zones = await response.json();
            const zone = zones.find((item) => String(item.id) === String(this.zoneId));
            this.slots = zone ? zone.slots : [];
        } catch (error) {
            console.error('Unable to load parking slots.', error);
        }
    },

    duration(timeIn) {
        if (!timeIn) return '—';

        const start = new Date(timeIn.replace(' ', 'T'));
        const seconds = Math.max(0, Math.floor((this.now - start.getTime()) / 1000));
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);

        if (hours > 0) return `${hours}h ${minutes}m`;
        return `${minutes}m`;
    },

    pollLiveStatus() {
        document.documentElement.dataset.liveSync = 'synced';
        document.querySelectorAll('[data-live-sync]').forEach((element) => {
            element.dataset.syncedAt = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        });
    },

    selectZone(zoneId) {
        this.selectedZone = String(zoneId);
        const zoneElement = document.getElementById(`parking-zone-${zoneId}`);
        if (zoneElement) {
            zoneElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
            zoneElement.classList.add('zone-map-selected');
            window.setTimeout(() => zoneElement.classList.remove('zone-map-selected'), 1800);
        }
    },

    locateSlot(slotId, zoneId) {
        this.selectZone(zoneId);
        window.setTimeout(() => {
            const slotElement = document.getElementById(`parking-slot-${slotId}`);
            if (slotElement) {
                slotElement.classList.add('slot-locate-pulse');
                slotElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                window.setTimeout(() => slotElement.classList.remove('slot-locate-pulse'), 5000);
            }
        }, 500);
    },
}));

Alpine.start();

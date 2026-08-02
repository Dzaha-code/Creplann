import Alpine from 'alpinejs';
import { plannerApi } from './planner/api';

window.Alpine = Alpine;
window.plannerApi = plannerApi;

const priorityLabels = {
    low: 'Rendah',
    medium: 'Sedang',
    high: 'Tinggi',
};

function formatTime(time) {
    if (!time) return '';
    return time.slice(0, 5);
}

function shiftDate(dateStr, days) {
    const date = new Date(`${dateStr}T12:00:00`);
    date.setDate(date.getDate() + days);
    return date.toISOString().slice(0, 10);
}

Alpine.data('weeklyPlanner', () => ({
    loading: true,
    saving: false,
    toast: '',
    toastType: 'success',
    week: null,
    days: [],
    summary: null,
    anchorDate: new Date().toISOString().slice(0, 10),
    showModal: false,
    editing: null,
    form: {
        title: '',
        description: '',
        date: '',
        start_time: '09:00',
        end_time: '10:00',
        priority: 'medium',
        color: '',
    },
    errors: {},

    async init() {
        await this.loadGrid();
    },

    async loadGrid() {
        this.loading = true;
        try {
            const data = await plannerApi.get(`/planner-api/weekly-grid?date=${this.anchorDate}`);
            this.week = data.week;
            this.days = data.days;
            this.summary = data.summary;
            this.anchorDate = data.week.anchor_date;
        } catch {
            this.notify('Gagal memuat jadwal mingguan.', 'error');
        } finally {
            this.loading = false;
        }
    },

    prevWeek() {
        this.anchorDate = shiftDate(this.week.start_date, -7);
        this.loadGrid();
    },

    nextWeek() {
        this.anchorDate = shiftDate(this.week.start_date, 7);
        this.loadGrid();
    },

    goToday() {
        this.anchorDate = new Date().toISOString().slice(0, 10);
        this.loadGrid();
    },

    openCreate(date) {
        this.editing = null;
        this.errors = {};
        this.form = {
            title: '',
            description: '',
            date: date || this.anchorDate,
            start_time: '09:00',
            end_time: '10:00',
            priority: 'medium',
            color: '',
        };
        this.showModal = true;
    },

    openEdit(schedule) {
        this.editing = schedule;
        this.errors = {};
        this.form = {
            title: schedule.title,
            description: schedule.description || '',
            date: schedule.date,
            start_time: formatTime(schedule.start_time),
            end_time: formatTime(schedule.end_time),
            priority: schedule.priority,
            color: schedule.color || '',
        };
        this.showModal = true;
    },

    closeModal() {
        this.showModal = false;
        this.editing = null;
        this.errors = {};
    },

    async saveSchedule() {
        this.saving = true;
        this.errors = {};
        try {
            if (this.editing) {
                await plannerApi.patch(`/planner-api/schedules/${this.editing.id}`, this.form);
                this.notify('Jadwal berhasil diperbarui.');
            } else {
                await plannerApi.post('/planner-api/schedules', this.form);
                this.notify('Jadwal berhasil ditambahkan.');
            }
            this.closeModal();
            await this.loadGrid();
        } catch (error) {
            this.errors = error.errors || {};
            this.notify(error.message || 'Gagal menyimpan jadwal.', 'error');
        } finally {
            this.saving = false;
        }
    },

    async deleteSchedule(schedule) {
        if (!confirm(`Hapus jadwal "${schedule.title}"?`)) return;
        try {
            await plannerApi.delete(`/planner-api/schedules/${schedule.id}`);
            this.notify('Jadwal berhasil dihapus.');
            await this.loadGrid();
        } catch {
            this.notify('Gagal menghapus jadwal.', 'error');
        }
    },

    async generateTodo(schedule) {
        try {
            const result = await plannerApi.post(`/planner-api/schedules/${schedule.id}/generate-todo`);
            this.notify(result.message || 'Todo berhasil dibuat.');
            await this.loadGrid();
        } catch {
            this.notify('Gagal membuat todo dari jadwal.', 'error');
        }
    },

    priorityLabel(priority) {
        return priorityLabels[priority] || priority;
    },

    notify(message, type = 'success') {
        this.toast = message;
        this.toastType = type;
        setTimeout(() => { this.toast = ''; }, 3200);
    },
}));

Alpine.data('notesPage', (config = {}) => ({
    showNoteModal: false,
    showCategoryModal: false,
    saving: false,
    editingNote: null,
    categories: config.categories || [],
    noteForm: { category_id: '', title: '', content: '' },
    categoryForm: { name: '', color: '' },
    noteErrors: {},
    categoryErrors: {},

    openCreateNote() {
        this.editingNote = null;
        this.noteErrors = {};
        this.noteForm = {
            category_id: this.categories[0]?.id ?? '',
            title: '',
            content: '',
        };
        this.showNoteModal = true;
    },

    openEditNote(note) {
        this.editingNote = note;
        this.noteErrors = {};
        this.noteForm = {
            category_id: note.category_id,
            title: note.title,
            content: note.content,
        };
        this.showNoteModal = true;
    },

    openCategoryModal() {
        this.categoryErrors = {};
        this.categoryForm = { name: '', color: '' };
        this.showCategoryModal = true;
    },

    async saveNote() {
        this.saving = true;
        this.noteErrors = {};
        try {
            if (this.editingNote) {
                await plannerApi.patch(`/planner-api/notes/${this.editingNote.id}`, this.noteForm);
            } else {
                await plannerApi.post('/planner-api/notes', this.noteForm);
            }
            window.location.reload();
        } catch (error) {
            this.noteErrors = error.errors || {};
            this.saving = false;
        }
    },

    async saveCategory() {
        this.saving = true;
        this.categoryErrors = {};
        try {
            await plannerApi.post('/planner-api/categories', this.categoryForm);
            window.location.reload();
        } catch (error) {
            this.categoryErrors = error.errors || {};
            this.saving = false;
        }
    },
}));

Alpine.start();

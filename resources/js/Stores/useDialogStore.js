import { defineStore } from 'pinia';

export const useDialogStore = defineStore('dialog', {
    state: () => ({
        noteDialogVisible: false,
        personDialogVisible: false,
        addressDialogVisible: false,
    }),
    actions: {
        openNoteDialog() {
            this.noteDialogVisible = true;
        },
        closeNoteDialog() {
            this.noteDialogVisible = false;
        },
        openPersonDialog() {
            this.personDialogVisible = true;
        },
        closePersonDialog() {
            this.personDialogVisible = false;
        },
        openAddressDialog() {
            this.addressDialogVisible = true;
        },
        closeAddressDialog() {
            this.addressDialogVisible = false;
        },
    },
});

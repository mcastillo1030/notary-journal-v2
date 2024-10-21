import { defineStore } from 'pinia';

export const useDialogStore = defineStore('dialog', {
    state: () => ({
        noteDialogVisible: false,
        personDialogVisible: false,
        addressEditDialogVisible: false,
        addressLinkDialogVisible: false,
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
        openAddressEditDialog() {
            this.addressEditDialogVisible = true;
        },
        closeAddressEditDialog() {
            this.addressEditDialogVisible = false;
        },
        openAddressLinkDialog() {
            this.addressLinkDialogVisible = true;
        },
        closeAddressLinkDialog() {
            this.addressLinkDialogVisible = false;
        },
    },
});

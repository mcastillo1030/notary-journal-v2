import { useConfirm } from 'primevue/useconfirm';
import { ref, toValue } from 'vue';

const useDestroyConfirmation = (data) => {
    const id = ref(toValue(data).id);
    const type = ref(toValue(data).type);

    if (!id.value || !type.value) {
        return;
    }

    // const confirmDelete = inject('confirmDelete', () => console.log('here'));
    const confirm = useConfirm();
    // const showSuccessToast = inject('showSuccessToast');
    // const showErrorToast = inject('showErrorToast');
    // const showUnknownErrorToast = inject('showUnknownErrorToast');

    // const destroyEvents = ['person.destroy', 'address.destroy', 'note.destroy'];

    // const partialReload = ({ model = '', toast = false, message = '' }) => {
    //     if (!model) {
    //         return;
    //     }

    //     const propsMap = {
    //         person: 'person',
    //         address: 'addresses',
    //         note: 'notes',
    //     };

    //     router.visit(
    //         route(routeNames.person.show, { person: route().params.person }),
    //         {
    //             only: [propsMap[model]],
    //             onFinish: () => {
    //                 toast && showSuccessToast(message);
    //             },
    //         },
    //     );
    // };

    // const handleAxiosResponse = (response) => {
    //     if (!response.data) {
    //         return;
    //     }

    //     const isSuccess =
    //         response.data.destroyed ||
    //         response.data.created ||
    //         response.data.updated;

    //     if (isSuccess) {
    //         !route().current(routeNames[response.data.type].index) &&
    //             partialReload({
    //                 model: response.data.type,
    //                 toast: true,
    //                 message: response.data.message,
    //             });
    //         route().current(routeNames[response.data.type].index) &&
    //             router.reload({
    //                 onFinish: showSuccessToast(response.data.message),
    //             });
    //         // !route().current(routeNames.person.index) &&
    //         //     partialReload(response.data.type, isSuccess);
    //         // route().current(routeNames.person.index) &&
    //         //     router.reload({
    //         //         onFinish: showSuccessToast(response.data.message),
    //         //     });
    //     } else {
    //         if (response.data.errors) {
    //             // TODO: display errors somehow
    //             // errors.value = response.data.errors;
    //         }
    //         showErrorToast(response.data.message);
    //     }
    // };

    const params = {};
    params[type.value] = id.value;

    if (type.value === 'note') {
        params.person = route().params.person;
    }

    confirm.require({
        message: `Do you want to delete this ${type.value}?`,
        header: 'Danger Zone',
        icon: 'pi pi-info-circle',
        rejectLabel: 'Cancel',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: 'Delete',
            severity: 'danger',
        },
        accept: () => {
            console.log('delete');
        },
    });
};

export default useDestroyConfirmation;

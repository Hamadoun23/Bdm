import { Head } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import UserForm from './Form';

export default function UsersCreate({ agences }) {
    return (
        <AppLayout title="Nouvel utilisateur" subtitle="Créer un compte commercial, téléphonique ou direction">
            <Head title="Nouvel utilisateur" />
            <UserForm agences={agences} />
        </AppLayout>
    );
}

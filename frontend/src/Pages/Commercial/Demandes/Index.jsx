import { Head } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import { Card } from '@/Components/ui/Card';
import Badge from '@/Components/ui/Badge';
import Pagination from '@/Components/ui/Pagination';

const TONS = { en_attente: 'amber', acceptee: 'green', refusee: 'red' };

export default function MesDemandes({ demandes }) {
    return (
        <AppLayout title="Mes demandes" subtitle="Ventes à des clients déjà enregistrés, soumises à l'administrateur">
            <Head title="Mes demandes" />

            <Card className="overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-sm">
                        <thead>
                            <tr className="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-500">
                                <th className="px-5 py-3 font-medium">Envoyée le</th>
                                <th className="px-5 py-3 font-medium">Client</th>
                                <th className="px-5 py-3 font-medium">Carte</th>
                                <th className="px-5 py-3 font-medium">Votre motif</th>
                                <th className="px-5 py-3 font-medium">Décision</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {demandes.data.map((d) => (
                                <tr key={d.id}>
                                    <td className="whitespace-nowrap px-5 py-3 text-gray-600">{d.date}</td>
                                    <td className="px-5 py-3">
                                        <span className="font-medium text-gray-900">{d.nom_complet}</span>
                                        <div className="text-xs text-gray-500">{d.telephone}</div>
                                    </td>
                                    <td className="px-5 py-3"><Badge tone="blue">{d.type_carte}</Badge></td>
                                    <td className="px-5 py-3 text-gray-600">{d.motif || '—'}</td>
                                    <td className="px-5 py-3">
                                        <Badge tone={TONS[d.statut]}>{d.statut_libelle}</Badge>
                                        {d.traite_le && <div className="mt-1 text-xs text-gray-500">le {d.traite_le}</div>}
                                        {d.commentaire_admin && <div className="mt-1 text-xs text-gray-700">« {d.commentaire_admin} »</div>}
                                        {d.statut === 'acceptee' && <div className="mt-1 text-xs text-green-700">Vente enregistrée</div>}
                                    </td>
                                </tr>
                            ))}
                            {demandes.data.length === 0 && (
                                <tr><td colSpan={5} className="px-5 py-8 text-center text-gray-500">Aucune demande.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
                <Pagination links={demandes.links} from={demandes.from} to={demandes.to} total={demandes.total} />
            </Card>
        </AppLayout>
    );
}

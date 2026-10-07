import { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import { Check, X, AlertTriangle } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import { Card } from '@/Components/ui/Card';
import Badge from '@/Components/ui/Badge';
import Button from '@/Components/ui/Button';
import { Textarea } from '@/Components/ui/Input';
import Pagination from '@/Components/ui/Pagination';

const TONS = { en_attente: 'amber', acceptee: 'green', refusee: 'red' };

function Demande({ d }) {
    const [commentaire, setCommentaire] = useState('');
    const [envoi, setEnvoi] = useState(false);
    const enAttente = d.statut === 'en_attente';

    function traiter(action) {
        if (action === 'refuser' && !commentaire.trim()
            && !confirm('Refuser sans commentaire ? Le commercial ne saura pas pourquoi.')) {
            return;
        }
        setEnvoi(true);
        router.post(route(`admin.demandes.${action}`, d.id), { commentaire }, {
            preserveScroll: true,
            onFinish: () => setEnvoi(false),
        });
    }

    return (
        <Card className="overflow-hidden">
            <div className="flex flex-wrap items-start justify-between gap-3 border-b border-gray-100 px-5 py-4">
                <div>
                    <div className="flex flex-wrap items-center gap-2">
                        <span className="text-base font-semibold text-gray-900">{d.nom_complet}</span>
                        <span className="text-sm text-gray-600">{d.telephone}</span>
                        <Badge tone="blue">{d.type_carte}</Badge>
                        {d.meme_type_carte && <Badge tone="red">A déjà cette carte</Badge>}
                        <Badge tone={TONS[d.statut]}>{d.statut_libelle}</Badge>
                    </div>
                    <p className="mt-1 text-sm text-gray-600">
                        Demandé par <strong className="text-gray-800">{d.commercial}</strong>
                        {d.agence && <> ({d.agence})</>} le {d.date}
                        {d.campagne && <> · {d.campagne}</>}
                        {(d.ville || d.quartier) && <> · {[d.ville, d.quartier].filter(Boolean).join(', ')}</>}
                    </p>
                    <p className="mt-2 text-sm text-gray-700">
                        <span className="text-gray-500">Motif du commercial : </span>
                        {d.motif || <em className="text-gray-400">aucun motif donné</em>}
                    </p>
                    {!enAttente && (
                        <p className="mt-1 text-sm text-gray-600">
                            {d.statut_libelle} par {d.traite_par} le {d.traite_le}
                            {d.commentaire_admin && <> — « {d.commentaire_admin} »</>}
                        </p>
                    )}
                </div>
            </div>

            <div className="overflow-x-auto">
                <p className="px-5 pt-3 text-xs font-medium uppercase tracking-wide text-gray-500">
                    Déjà dans la base avec ce numéro ({d.fiches.length})
                </p>
                <table className="w-full text-left text-sm">
                    <thead>
                        <tr className="text-xs uppercase tracking-wide text-gray-500">
                            <th className="px-5 py-2 font-medium">Enregistré le</th>
                            <th className="px-5 py-2 font-medium">Type</th>
                            <th className="px-5 py-2 font-medium">Nom</th>
                            <th className="px-5 py-2 font-medium">Carte / compte</th>
                            <th className="px-5 py-2 font-medium">Commercial</th>
                            <th className="px-5 py-2 font-medium">Campagne</th>
                            <th className="px-5 py-2"></th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-100">
                        {d.fiches.map((f, i) => (
                            <tr key={`${f.nature}-${f.id ?? i}`} className={f.meme_type_carte ? 'bg-red-50' : undefined}>
                                <td className="whitespace-nowrap px-5 py-2 text-gray-700">{f.date}</td>
                                <td className="px-5 py-2"><Badge tone={f.nature === 'Vente' ? 'blue' : 'amber'}>{f.nature}</Badge></td>
                                <td className="px-5 py-2 font-medium text-gray-900">{f.nom_complet}</td>
                                <td className="px-5 py-2 text-gray-600">
                                    {f.detail}
                                    {f.meme_type_carte && <div className="text-xs font-medium text-red-700">même carte que la demande</div>}
                                </td>
                                <td className="px-5 py-2 text-gray-600">
                                    {f.commercial}
                                    {f.agence && <div className="text-xs text-gray-400">{f.agence}</div>}
                                    {f.meme_commercial && <div className="text-xs text-gray-400">le même commercial</div>}
                                </td>
                                <td className="px-5 py-2 text-gray-600">{f.campagne ?? '—'}</td>
                                <td className="px-5 py-2 text-right">
                                    {f.id && <Button href={route('clients.show', f.id)} target="_blank" size="sm" variant="outline">Détail</Button>}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {enAttente && (
                <div className="flex flex-wrap items-end gap-3 border-t border-gray-100 bg-gray-50 px-5 py-4">
                    <div className="min-w-[16rem] flex-1">
                        <label className="mb-1.5 block text-xs font-medium text-gray-500">Commentaire pour le commercial</label>
                        <Textarea rows={2} value={commentaire} onChange={(e) => setCommentaire(e.target.value)} placeholder="Facultatif pour une validation, conseillé pour un refus" />
                    </div>
                    <Button type="button" disabled={envoi} onClick={() => traiter('valider')}>
                        <Check size={15} /> Valider : enregistrer la vente
                    </Button>
                    <Button type="button" variant="outline" disabled={envoi} onClick={() => traiter('refuser')}>
                        <X size={15} /> Refuser
                    </Button>
                </div>
            )}
        </Card>
    );
}

export default function DemandesIndex({ demandes, filters = {}, compteurs, statuts }) {
    const statut = filters.statut || 'en_attente';
    return (
        <AppLayout title="Demandes à valider" subtitle="Ventes à des clients déjà enregistrés">
            <Head title="Demandes à valider" />

            <div className="mb-4 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                <AlertTriangle size={18} className="mt-0.5 shrink-0" />
                <p>
                    Un commercial ne peut plus enregistrer seul une vente pour un client déjà présent dans la base.
                    Comparez la demande aux fiches existantes : <strong>Valider</strong> crée la vente (datée du jour de la
                    demande), <strong>Refuser</strong> n'enregistre rien. Les lignes en rouge sont les cartes du même type
                    que celle demandée.
                </p>
            </div>

            <div className="mb-4 flex flex-wrap gap-2">
                {statuts.map((s) => (
                    <Button
                        key={s.id}
                        type="button"
                        size="sm"
                        variant={statut === s.id ? 'primary' : 'outline'}
                        onClick={() => router.get(route('admin.demandes.index'), { statut: s.id })}
                    >
                        {s.nom} ({compteurs[s.id] ?? 0})
                    </Button>
                ))}
            </div>

            <div className="space-y-4">
                {demandes.data.length === 0 && (
                    <Card className="p-8 text-center text-sm text-gray-500">Aucune demande.</Card>
                )}
                {demandes.data.map((d) => <Demande key={d.id} d={d} />)}
                {demandes.total > 0 && (
                    <Card className="overflow-hidden">
                        <Pagination links={demandes.links} from={demandes.from} to={demandes.to} total={demandes.total} />
                    </Card>
                )}
            </div>
        </AppLayout>
    );
}

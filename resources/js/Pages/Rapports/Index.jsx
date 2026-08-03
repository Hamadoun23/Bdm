import { useMemo, useState } from 'react';
import { Head } from '@inertiajs/react';
import { Info } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import { Card, CardHeader, CardTitle } from '@/Components/ui/Card';
import Badge from '@/Components/ui/Badge';
import Button from '@/Components/ui/Button';

export default function RapportsIndex({ campagnes, libelleStatsCampagne, isAdmin }) {
    const [selected, setSelected] = useState([]);
    const allChecked = selected.length === campagnes.length && campagnes.length > 0;

    function toggle(id) {
        setSelected((s) => (s.includes(id) ? s.filter((i) => i !== id) : [...s, id]));
    }

    function toggleAll() {
        setSelected(allChecked ? [] : campagnes.map((c) => c.id));
    }

    const cumulUrl = useMemo(() => {
        const params = new URLSearchParams();
        selected.forEach((id) => params.append('campagne_ids[]', id));
        return route('rapports.cumul') + '?' + params.toString();
    }, [selected]);

    return (
        <AppLayout title="Rapports" subtitle="Synthèses et exports par campagne">
            <Head title="Rapports" />

            <div className="mb-4 flex items-start gap-2 rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-600">
                <Info size={16} className="mt-0.5 shrink-0 text-gray-400" />
                <div className="space-y-1.5">
                    <p><strong>Cumul multi-campagnes :</strong> cochez une ou plusieurs lignes ci-dessous, puis « Voir le cumul » pour une vue agrégée (ventes, commerciaux, agences, types de carte, clients). Même accès depuis <a href={route('performances.index')} className="text-gda-orange hover:underline">Performances</a> (lien « Cumul »).</p>
                    <p><strong>Usage direction / pilotage :</strong> « Export complet » sur chaque campagne génère un classeur Excel (ventes, clients, commerciaux, agences, types de carte, semaines, mois, fiches téléphonique + synthèse appels). « Synthèse » pour les graphiques et exports filtrés, « Ventes » pour le détail avec filtres.</p>
                    <p className="text-gray-400">Les ventes sont enregistrées avec le <code>campagne_id</code> de la campagne active au moment de la saisie.</p>
                    {libelleStatsCampagne && <p><strong>Périmètre stats par défaut :</strong> {libelleStatsCampagne} (campagnes en cours, sinon dernière campagne).</p>}
                    {isAdmin && (
                        <p>
                            <a href={route('admin.telephonique-rapports.index')} className="text-gda-orange hover:underline">Reporting téléphonique (toutes campagnes)</a> — vue globale.
                            Depuis chaque campagne : bouton <strong>Tél.</strong> ou entrée <strong>Synthèse</strong> pour le périmètre campagne.
                        </p>
                    )}
                </div>
            </div>

            <Card className="overflow-hidden">
                <CardHeader className="flex flex-wrap items-center justify-between gap-2">
                    <CardTitle>Campagnes</CardTitle>
                    <div className="flex gap-2">
                        <Button type="button" variant="outline" size="sm" onClick={toggleAll}>
                            {allChecked ? 'Tout désélectionner' : 'Tout sélectionner'}
                        </Button>
                        <Button href={cumulUrl} size="sm" disabled={selected.length === 0} variant="secondary">
                            Voir le cumul
                        </Button>
                    </div>
                </CardHeader>
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-sm">
                        <thead>
                            <tr className="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-500">
                                <th className="px-4 py-3"></th>
                                <th className="px-4 py-3 font-medium">Nom</th>
                                <th className="px-4 py-3 font-medium">Période</th>
                                <th className="px-4 py-3 font-medium">Statut</th>
                                <th className="px-4 py-3 text-right font-medium">Ventes</th>
                                <th className="px-4 py-3 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {campagnes.map((c) => (
                                <tr key={c.id} className="hover:bg-gray-50">
                                    <td className="px-4 py-3">
                                        <input
                                            type="checkbox"
                                            checked={selected.includes(c.id)}
                                            onChange={() => toggle(c.id)}
                                            className="h-4 w-4 rounded border-gray-300 text-gda-orange focus:ring-gda-orange/40"
                                        />
                                    </td>
                                    <td className="px-4 py-3 font-medium text-gray-900">{c.nom}</td>
                                    <td className="px-4 py-3 text-gray-600">{c.date_debut} → {c.date_fin}</td>
                                    <td className="px-4 py-3"><Badge>{c.statut}</Badge></td>
                                    <td className="px-4 py-3 text-right text-gray-600">{c.nb_ventes}</td>
                                    <td className="px-4 py-3">
                                        <div className="flex flex-wrap gap-1.5">
                                            <Button href={route('rapports.campagnes.export', { campagne: c.id, section: 'all', format: 'xlsx' })} target="_blank" size="sm">Export complet</Button>
                                            <Button href={route('rapports.campagnes.synthese', c.id)} variant="outline" size="sm">Synthèse</Button>
                                            <Button href={route('rapports.campagnes.ventes', c.id)} size="sm">Ventes</Button>
                                            <Button href={route('rapports.campagnes.clients', c.id)} variant="outline" size="sm">Clients</Button>
                                            <Button href={route('rapports.campagnes.reporting-telephonique', c.id)} variant="outline" size="sm">Tél.</Button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                            {campagnes.length === 0 && (
                                <tr>
                                    <td colSpan={6} className="px-4 py-8 text-center text-gray-500">Aucune campagne à afficher.</td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </Card>
        </AppLayout>
    );
}

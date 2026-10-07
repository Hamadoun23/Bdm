import { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import { Search } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import { Card } from '@/Components/ui/Card';
import Badge from '@/Components/ui/Badge';
import Button from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Select } from '@/Components/ui/Select';
import Pagination from '@/Components/ui/Pagination';

const VIDE = {
    q: '', type_carte_id: '', user_id: '', agence_id: '', campagne_id: '',
    statut: '', ville: '', du: '', au: '', doublons: '',
};

function Champ({ label, className = 'w-44', children }) {
    return (
        <div className={className}>
            <label className="mb-1.5 block text-xs font-medium text-gray-500">{label}</label>
            {children}
        </div>
    );
}

function Liste({ value, onChange, options, tous = 'Tous' }) {
    return (
        <Select value={value} onChange={(e) => onChange(e.target.value)}>
            <option value="">{tous}</option>
            {options.map((o) => (
                <option key={o.id} value={o.id}>{o.nom}</option>
            ))}
        </Select>
    );
}

export default function ClientsIndex({ clients, filters = {}, choix }) {
    const [f, setF] = useState({ ...VIDE, ...filters });
    const set = (cle) => (valeur) => setF((p) => ({ ...p, [cle]: valeur }));

    function appliquer(e) {
        e?.preventDefault();
        const params = Object.fromEntries(Object.entries(f).filter(([, v]) => v !== ''));
        router.get(route('clients.index'), params, { preserveState: true });
    }

    function reinitialiser() {
        setF(VIDE);
        router.get(route('clients.index'));
    }

    const actifs = Object.values(filters).some(Boolean);

    return (
        <AppLayout title="Clients" subtitle="Liste de tous les clients enregistrés">
            <Head title="Clients" />

            <form onSubmit={appliquer} className="mb-4 flex flex-wrap items-end gap-3">
                <Champ label="Recherche" className="w-full max-w-xs">
                    <Input
                        value={f.q}
                        onChange={(e) => set('q')(e.target.value)}
                        placeholder="Nom, prénom, téléphone, quartier…"
                    />
                </Champ>
                <Champ label="Type de carte">
                    <Liste value={f.type_carte_id} onChange={set('type_carte_id')} options={choix.types} />
                </Champ>
                <Champ label="Commercial" className="w-52">
                    <Liste value={f.user_id} onChange={set('user_id')} options={choix.commerciaux} />
                </Champ>
                {choix.agences.length > 0 && (
                    <Champ label="Agence" className="w-52">
                        <Liste value={f.agence_id} onChange={set('agence_id')} options={choix.agences} tous="Toutes" />
                    </Champ>
                )}
                <Champ label="Campagne" className="w-56">
                    <Liste value={f.campagne_id} onChange={set('campagne_id')} options={choix.campagnes} tous="Toutes" />
                </Champ>
                <Champ label="Ville" className="w-40">
                    <Liste
                        value={f.ville}
                        onChange={set('ville')}
                        options={choix.villes.map((v) => ({ id: v, nom: v }))}
                        tous="Toutes"
                    />
                </Champ>
                <Champ label="Statut" className="w-36">
                    <Liste value={f.statut} onChange={set('statut')} options={choix.statuts} />
                </Champ>
                <Champ label="Ajouté du" className="w-40">
                    <Input type="date" value={f.du} onChange={(e) => set('du')(e.target.value)} />
                </Champ>
                <Champ label="au" className="w-40">
                    <Input type="date" value={f.au} onChange={(e) => set('au')(e.target.value)} />
                </Champ>
                <label className="flex h-[42px] items-center gap-2 text-sm text-gray-700">
                    <input
                        type="checkbox"
                        className="h-4 w-4 rounded border-gray-300 text-gda-orange focus:ring-gda-orange/30"
                        checked={f.doublons === '1'}
                        onChange={(e) => set('doublons')(e.target.checked ? '1' : '')}
                    />
                    Numéros en double
                </label>
                <Button type="submit" variant="outline"><Search size={14} /> Filtrer</Button>
                {actifs && <Button type="button" variant="ghost" onClick={reinitialiser}>Réinitialiser</Button>}
            </form>

            <Card className="overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-sm">
                        <thead>
                            <tr className="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-500">
                                <th className="px-5 py-3 font-medium">Nom</th>
                                <th className="px-5 py-3 font-medium">Téléphone</th>
                                <th className="px-5 py-3 font-medium">Ville</th>
                                <th className="px-5 py-3 font-medium">Type carte</th>
                                <th className="px-5 py-3 font-medium">Commercial</th>
                                <th className="px-5 py-3 font-medium">Campagne</th>
                                <th className="px-5 py-3 font-medium">Ajouté le</th>
                                <th className="px-5 py-3 font-medium">Statut</th>
                                <th className="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {clients.data.map((c) => (
                                <tr key={c.id} className="hover:bg-gray-50">
                                    <td className="px-5 py-3 font-medium text-gray-900">{c.nom_complet}</td>
                                    <td className="whitespace-nowrap px-5 py-3 text-gray-600">
                                        {c.telephone ?? '—'}
                                        {c.fiches_meme_numero > 1 && (
                                            <span className="ml-2"><Badge tone="red">×{c.fiches_meme_numero} fiches</Badge></span>
                                        )}
                                    </td>
                                    <td className="px-5 py-3 text-gray-600">{c.ville ?? '—'}</td>
                                    <td className="px-5 py-3"><Badge tone="blue">{c.type_carte}</Badge></td>
                                    <td className="px-5 py-3 text-gray-600">
                                        {c.commercial}
                                        {c.agence && <div className="text-xs text-gray-400">{c.agence}</div>}
                                    </td>
                                    <td className="px-5 py-3 text-gray-600">{c.campagne ?? '—'}</td>
                                    <td className="whitespace-nowrap px-5 py-3 text-gray-600">{c.date ?? '—'}</td>
                                    <td className="px-5 py-3"><Badge>{c.statut_carte}</Badge></td>
                                    <td className="px-5 py-3 text-right">
                                        <Button href={route('clients.show', c.id)} size="sm">Détail</Button>
                                    </td>
                                </tr>
                            ))}
                            {clients.data.length === 0 && (
                                <tr>
                                    <td colSpan={9} className="px-5 py-8 text-center text-gray-500">Aucun client.</td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
                <Pagination links={clients.links} from={clients.from} to={clients.to} total={clients.total} />
            </Card>
        </AppLayout>
    );
}

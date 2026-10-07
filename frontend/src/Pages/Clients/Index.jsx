import { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import { Search, Download, Users, UserCheck, Copy, AlertTriangle, Repeat } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import { Card, CardHeader, CardTitle } from '@/Components/ui/Card';
import Badge from '@/Components/ui/Badge';
import Button from '@/Components/ui/Button';
import StatCard from '@/Components/ui/StatCard';
import { Input } from '@/Components/ui/Input';
import { Select } from '@/Components/ui/Select';
import Pagination from '@/Components/ui/Pagination';

const VIDE = {
    q: '', type_carte_id: '', user_id: '', agence_id: '', campagne_id: '',
    statut: '', ville: '', du: '', au: '', doublons: '', cas: '',
    delai: '', campagne_resaisie: '', resaisie_du: '', resaisie_au: '', tri: '',
};

const SAISIE = {
    originale: { label: '1ère saisie', tone: 'green' },
    resaisie_autre: { label: "Client d'un autre commercial", tone: 'red' },
    resaisie_meme: { label: 'Re-saisie même commercial', tone: 'amber' },
};

const nombre = (n) => new Intl.NumberFormat('fr-FR').format(n ?? 0);

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
            {tous !== null && <option value="">{tous}</option>}
            {options.map((o) => (
                <option key={o.id} value={o.id}>{o.nom}</option>
            ))}
        </Select>
    );
}

/** Répartition en barres horizontales : une ligne cliquable par valeur. */
function Repartition({ titre, lignes, total, actif, onChoisir }) {
    const max = Math.max(1, ...lignes.map((l) => l.total));
    return (
        <Card className="overflow-hidden">
            <CardHeader><CardTitle>{titre}</CardTitle></CardHeader>
            {lignes.length === 0 ? (
                <p className="px-5 pb-5 text-sm text-gray-500">Aucune donnée.</p>
            ) : (
                <ul className="max-h-72 overflow-y-auto px-2 pb-3">
                    {lignes.map((l) => {
                        const pct = total ? Math.round((1000 * l.total) / total) / 10 : 0;
                        const selectionne = String(actif) === String(l.id);
                        return (
                            <li key={l.id}>
                                <button
                                    type="button"
                                    onClick={() => onChoisir(selectionne ? '' : String(l.id))}
                                    title={`${l.nom} : ${nombre(l.total)} clients (${pct} %) — cliquer pour filtrer`}
                                    className={`group w-full rounded-lg px-3 py-1.5 text-left hover:bg-gray-50 ${selectionne ? 'bg-orange-50' : ''}`}
                                >
                                    <div className="flex items-baseline justify-between gap-3 text-sm">
                                        <span className="truncate text-gray-700">{l.nom}</span>
                                        <span className="shrink-0 tabular-nums text-gray-900">
                                            {nombre(l.total)} <span className="text-xs text-gray-400">{pct} %</span>
                                        </span>
                                    </div>
                                    <div className="mt-1 h-1.5 w-full rounded-full bg-gray-100">
                                        <div
                                            className="h-1.5 rounded-full bg-gda-orange transition-all group-hover:opacity-80"
                                            style={{ width: `${(100 * l.total) / max}%` }}
                                        />
                                    </div>
                                </button>
                            </li>
                        );
                    })}
                </ul>
            )}
        </Card>
    );
}

function TableauDeBord({ tdb, filters, filtrer }) {
    return (
        <section className="mb-5 space-y-4">
            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <StatCard label="Clients" value={nombre(tdb.total)} sub="selon les filtres" icon={Users} />
                <StatCard label="Commerciaux" value={nombre(tdb.commerciaux)} sub="ayant saisi ces clients" icon={UserCheck} tone="blue" />
                <StatCard label="Campagnes" value={nombre(tdb.parCampagne.length)} sub="d'où viennent les clients" tone="gray" />
                <StatCard label="Agences" value={nombre(tdb.parAgence.length)} sub="d'où viennent les clients" tone="gray" />
            </div>
            <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <Repartition titre="Par campagne" lignes={tdb.parCampagne} total={tdb.total} actif={filters.campagne_id} onChoisir={(v) => filtrer({ campagne_id: v })} />
                <Repartition titre="Par agence" lignes={tdb.parAgence} total={tdb.total} actif={filters.agence_id} onChoisir={(v) => filtrer({ agence_id: v })} />
                <Repartition titre="Par commercial (top 15)" lignes={tdb.parCommercial} total={tdb.total} actif={filters.user_id} onChoisir={(v) => filtrer({ user_id: v })} />
                <Repartition titre="Par type de carte" lignes={tdb.parType} total={tdb.total} actif={filters.type_carte_id} onChoisir={(v) => filtrer({ type_carte_id: v })} />
                <Repartition titre="Par ville (top 15)" lignes={tdb.parVille} total={tdb.total} actif={filters.ville} onChoisir={(v) => filtrer({ ville: v })} />
            </div>
        </section>
    );
}

function ListeClients({ clients }) {
    return (
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
    );
}

/** Petite liste chiffrée (commerciaux concernés, campagnes, délais, mois). */
function Decompte({ titre, lignes, vide = 'Aucun.', onChoisir }) {
    return (
        <div>
            <p className="mb-1.5 text-xs font-medium uppercase tracking-wide text-gray-500">{titre}</p>
            {lignes.length === 0 ? (
                <p className="text-sm text-gray-400">{vide}</p>
            ) : (
                <ul className="space-y-1 text-sm">
                    {lignes.map((l) => (
                        <li key={l.id} className="flex items-baseline justify-between gap-3">
                            {onChoisir ? (
                                <button type="button" onClick={() => onChoisir(l)} className="truncate text-left text-gray-700 underline-offset-2 hover:text-gda-orange hover:underline">
                                    {l.nom}
                                </button>
                            ) : (
                                <span className="truncate text-gray-700">{l.nom}</span>
                            )}
                            <span className="shrink-0 tabular-nums font-medium text-gray-900">{nombre(l.total)}</span>
                        </li>
                    ))}
                </ul>
            )}
        </div>
    );
}

/** Bilan d'audit du commercial choisi dans le filtre « Commercial ». */
function AuditCommercial({ audit, cas, filtrer }) {
    const onglets = [
        { id: 'autre', label: "A ressaisi le client d'un autre", total: audit.resaisies_autre, tone: 'text-red-700' },
        { id: 'meme', label: 'A ressaisi son propre client', total: audit.resaisies_meme, tone: 'text-amber-700' },
        { id: 'victime', label: 'Ses clients ressaisis par un autre', total: audit.clients_ressaisis_par_autres, tone: 'text-blue-700' },
        { id: '', label: 'Tous ses doublons', total: null, tone: 'text-gray-900' },
    ];
    return (
        <Card className="overflow-hidden border-orange-200">
            <div className="flex flex-wrap items-baseline justify-between gap-2 border-b border-gray-100 px-5 py-4">
                <div>
                    <p className="text-xs font-medium uppercase tracking-wide text-gray-500">Audit du commercial</p>
                    <p className="text-lg font-semibold text-gray-900">{audit.commercial}</p>
                </div>
                <p className="text-sm text-gray-600">
                    {nombre(audit.total_fiches)} fiches saisies · <strong className="text-gray-900">{audit.pourcentage} %</strong> de re-saisies
                    {audit.premiere && <> · re-saisies du {audit.premiere} au {audit.derniere}</>}
                </p>
            </div>
            <div className="grid gap-px bg-gray-100 sm:grid-cols-2 lg:grid-cols-4">
                {onglets.map((o) => {
                    const actif = (cas || '') === o.id;
                    return (
                        <button
                            key={o.id || 'tous'}
                            type="button"
                            onClick={() => filtrer({ cas: o.id })}
                            className={`bg-white px-5 py-3 text-left hover:bg-orange-50 ${actif ? 'ring-2 ring-inset ring-gda-orange' : ''}`}
                        >
                            <p className="text-xs text-gray-500">{o.label}</p>
                            <p className={`text-2xl font-semibold tabular-nums ${o.tone}`}>{o.total === null ? '→' : nombre(o.total)}</p>
                            <p className="text-xs text-gray-400">{actif ? 'affiché ci-dessous' : 'cliquer pour afficher'}</p>
                        </button>
                    );
                })}
            </div>
            <div className="grid gap-6 px-5 py-4 md:grid-cols-2 xl:grid-cols-5">
                <Decompte
                    titre="Clients repris à"
                    lignes={audit.pris_a}
                    onChoisir={() => filtrer({ cas: 'autre' })}
                />
                <Decompte
                    titre="Ses clients repris par"
                    lignes={audit.pris_par}
                    onChoisir={() => filtrer({ cas: 'victime' })}
                />
                <Decompte titre="Re-saisies par campagne" lignes={audit.par_campagne} />
                <Decompte
                    titre="Délai après la 1ère saisie"
                    lignes={audit.par_delai}
                    onChoisir={(l) => filtrer({ delai: l.id })}
                />
                <Decompte titre="Re-saisies par mois" lignes={audit.par_mois} />
            </div>
        </Card>
    );
}

function Doublons({ doublons, filters, filtrer }) {
    const { stats, classement, groupes, audit } = doublons;
    return (
        <div className="space-y-4">
            {audit && <AuditCommercial audit={audit} cas={filters.cas} filtrer={filtrer} />}

            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <StatCard label="Clients en double" value={nombre(stats.groupes)} sub={`${nombre(stats.fiches)} fiches concernées`} icon={Copy} />
                <StatCard label="Client d'un autre commercial" value={nombre(stats.resaisies_autre)} sub="re-saisies retenues par les filtres" icon={AlertTriangle} tone="orange" />
                <StatCard label="Re-saisies même commercial" value={nombre(stats.resaisies_meme)} sub="double saisie ou carte en plus" icon={Repeat} tone="gray" />
                <StatCard label="Plusieurs commerciaux" value={nombre(stats.plusieurs_commerciaux)} sub="clients saisis par 2 commerciaux ou +" icon={Users} tone="blue" />
            </div>

            {!audit && classement.length > 0 && (
                <Card className="overflow-hidden">
                    <CardHeader><CardTitle>Commerciaux qui ressaisissent des clients</CardTitle></CardHeader>
                    <div className="overflow-x-auto">
                        <table className="w-full text-left text-sm">
                            <thead>
                                <tr className="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-500">
                                    <th className="px-5 py-2.5 font-medium">Commercial</th>
                                    <th className="px-5 py-2.5 text-right font-medium">Fiches saisies</th>
                                    <th className="px-5 py-2.5 text-right font-medium">Client d'un autre</th>
                                    <th className="px-5 py-2.5 text-right font-medium">Son propre client</th>
                                    <th className="px-5 py-2.5 text-right font-medium">% re-saisies</th>
                                    <th className="px-5 py-2.5"></th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {classement.map((c) => (
                                    <tr key={c.user_id} className="hover:bg-gray-50">
                                        <td className="px-5 py-2.5 font-medium text-gray-900">{c.commercial}</td>
                                        <td className="px-5 py-2.5 text-right tabular-nums text-gray-600">{nombre(c.total_fiches)}</td>
                                        <td className={`px-5 py-2.5 text-right tabular-nums ${c.autre ? 'font-semibold text-red-700' : 'text-gray-400'}`}>{nombre(c.autre)}</td>
                                        <td className="px-5 py-2.5 text-right tabular-nums text-gray-600">{nombre(c.meme)}</td>
                                        <td className="px-5 py-2.5 text-right tabular-nums text-gray-900">{c.pourcentage} %</td>
                                        <td className="px-5 py-2.5 text-right">
                                            <Button type="button" size="sm" variant="outline" onClick={() => filtrer({ user_id: String(c.user_id) })}>
                                                Auditer
                                            </Button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </Card>
            )}

            {groupes.data.length === 0 && (
                <Card className="p-8 text-center text-sm text-gray-500">Aucun doublon pour ces filtres.</Card>
            )}

            {groupes.data.map((g) => (
                <Card key={g.numero} className="overflow-hidden">
                    <div className="flex flex-wrap items-center gap-x-4 gap-y-1 border-b border-gray-100 px-5 py-3">
                        <span className="text-sm font-semibold text-gray-900">
                            {doublons.critere === 'nom' ? g.cle : `Numéro ${g.cle}`}
                        </span>
                        <Badge tone="red">{g.nb_fiches} fiches</Badge>
                        <span className="text-xs text-gray-500">
                            {g.nb_commerciaux > 1 ? `${g.nb_commerciaux} commerciaux différents` : 'un seul commercial'}
                            {' · '}1ère saisie par <strong className="text-gray-700">{g.premiere_par}</strong>
                            {' le '}<strong className="text-gray-700">{g.fiches[0].heure}</strong>
                        </span>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="w-full text-left text-sm">
                            <thead>
                                <tr className="text-xs uppercase tracking-wide text-gray-500">
                                    <th className="px-5 py-2 font-medium">Enregistré le</th>
                                    <th className="px-5 py-2 font-medium">Saisie</th>
                                    <th className="px-5 py-2 font-medium">Commercial</th>
                                    <th className="px-5 py-2 font-medium">Client</th>
                                    <th className="px-5 py-2 font-medium">Téléphone</th>
                                    <th className="px-5 py-2 font-medium">Carte</th>
                                    <th className="px-5 py-2 font-medium">Campagne</th>
                                    <th className="px-5 py-2"></th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {g.fiches.map((x) => {
                                    const s = SAISIE[x.statut_saisie];
                                    const fond = x.cible
                                        ? (x.statut_saisie === 'resaisie_autre' ? 'bg-red-50' : 'bg-amber-50/70')
                                        : '';
                                    return (
                                        <tr key={x.id} className={fond}>
                                            <td className={`whitespace-nowrap px-5 py-2.5 ${x.cible ? 'border-l-4 border-gda-orange' : 'border-l-4 border-transparent'}`}>
                                                <span className="font-medium text-gray-900">{x.heure}</span>
                                                {x.jours_apres !== null && (
                                                    <div className={`text-xs ${x.jours_apres === 0 ? 'text-gray-500' : 'font-medium text-gray-700'}`}>
                                                        {x.jours_apres === 0 ? 'le même jour que la 1ère' : `${x.jours_apres} j après la 1ère`}
                                                    </div>
                                                )}
                                            </td>
                                            <td className="whitespace-nowrap px-5 py-2.5"><Badge tone={s.tone}>{s.label}</Badge></td>
                                            <td className="min-w-[10rem] px-5 py-2.5 text-gray-700">
                                                {x.commercial}
                                                {x.agence && <div className="text-xs text-gray-400">{x.agence}</div>}
                                            </td>
                                            <td className="min-w-[10rem] px-5 py-2.5 font-medium text-gray-900">
                                                {x.nom_complet}
                                                {!x.dans_filtre && <div className="text-xs font-normal text-gray-400">hors filtres</div>}
                                            </td>
                                            <td className="whitespace-nowrap px-5 py-2.5 text-gray-600">{x.telephone ?? '—'}</td>
                                            <td className="px-5 py-2.5"><Badge tone="blue">{x.type_carte}</Badge></td>
                                            <td className="min-w-[9rem] px-5 py-2.5 text-gray-600">
                                                {x.campagne ?? '—'}
                                                {x.meme_campagne === true && <div className="text-xs text-gray-400">même campagne que la 1ère</div>}
                                                {x.meme_campagne === false && <div className="text-xs font-medium text-amber-700">autre campagne que la 1ère</div>}
                                            </td>
                                            <td className="px-5 py-2.5 text-right">
                                                <Button href={route('clients.show', x.id)} size="sm" variant="outline">Détail</Button>
                                            </td>
                                        </tr>
                                    );
                                })}
                            </tbody>
                        </table>
                    </div>
                </Card>
            ))}

            {groupes.total > 0 && (
                <Card className="overflow-hidden">
                    <Pagination links={groupes.links} from={groupes.from} to={groupes.to} total={groupes.total} />
                </Card>
            )}
        </div>
    );
}

export default function ClientsIndex({ clients, doublons, tableauDeBord, filters = {}, choix }) {
    const actuels = { ...VIDE, ...filters };
    const [f, setF] = useState(actuels);
    const set = (cle) => (valeur) => setF((p) => ({ ...p, [cle]: valeur }));

    const nettoyer = (valeurs) => Object.fromEntries(Object.entries(valeurs).filter(([, v]) => v !== ''));

    function envoyer(valeurs) {
        router.get(route('clients.index'), nettoyer(valeurs), { preserveState: true, preserveScroll: true });
    }

    function appliquer(e) {
        e?.preventDefault();
        envoyer(f);
    }

    // Clic sur une barre du tableau de bord ou sur un commercial du classement.
    function filtrer(changement) {
        const valeurs = { ...actuels, ...changement };
        setF(valeurs);
        envoyer(valeurs);
    }

    function reinitialiser() {
        setF(VIDE);
        router.get(route('clients.index'));
    }

    const actifs = Object.values(filters).some(Boolean);
    const modeDoublons = Boolean(doublons);
    const urlExport = route('clients.doublons.export', nettoyer({ ...actuels, doublons: actuels.doublons || 'numero' }));

    return (
        <AppLayout
            title="Clients"
            subtitle="D'où viennent les clients, et contrôle des doublons"
            actions={
                modeDoublons && (
                    <Button href={urlExport} target="_blank" variant="outline" size="sm">
                        <Download size={14} /> Exporter les doublons
                    </Button>
                )
            }
        >
            <Head title="Clients" />

            <form onSubmit={appliquer} className="mb-5 space-y-3">
                <div className="flex flex-wrap items-end gap-3">
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
                            <Liste
                                value={f.agence_id}
                                onChange={set('agence_id')}
                                options={[...choix.agences, { id: 'aucune', nom: 'Sans agence' }]}
                                tous="Toutes"
                            />
                        </Champ>
                    )}
                    <Champ label="Campagne" className="w-56">
                        <Liste
                            value={f.campagne_id}
                            onChange={set('campagne_id')}
                            options={[...choix.campagnes, { id: 'aucune', nom: 'Sans campagne' }]}
                            tous="Toutes"
                        />
                    </Champ>
                    <Champ label="Ville" className="w-40">
                        <Liste
                            value={f.ville}
                            onChange={set('ville')}
                            options={[...choix.villes.map((v) => ({ id: v, nom: v })), { id: 'aucune', nom: 'Non renseignée' }]}
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
                </div>

                <div className="flex flex-wrap items-end gap-3 rounded-xl border border-orange-200 bg-orange-50/50 p-3">
                    <Champ label="Contrôle des doublons — détecter" className="w-64">
                        <Liste
                            value={f.doublons === '1' ? 'numero' : f.doublons}
                            onChange={set('doublons')}
                            options={choix.criteres}
                            tous="Non (liste normale)"
                        />
                    </Champ>
                    <Champ label="Afficher" className="w-80">
                        <Liste value={f.cas} onChange={set('cas')} options={choix.cas} tous={null} />
                    </Champ>
                    <Champ label="Délai de la re-saisie" className="w-52">
                        <Liste value={f.delai} onChange={set('delai')} options={choix.delais} tous="Tous les délais" />
                    </Champ>
                    <Champ label="Campagne de la re-saisie" className="w-72">
                        <Liste value={f.campagne_resaisie} onChange={set('campagne_resaisie')} options={choix.campagnesResaisie} tous="Peu importe" />
                    </Champ>
                    <Champ label="Re-saisie du" className="w-40">
                        <Input type="date" value={f.resaisie_du} onChange={(e) => set('resaisie_du')(e.target.value)} />
                    </Champ>
                    <Champ label="au" className="w-40">
                        <Input type="date" value={f.resaisie_au} onChange={(e) => set('resaisie_au')(e.target.value)} />
                    </Champ>
                    <Champ label="Trier par" className="w-72">
                        <Liste value={f.tri} onChange={set('tri')} options={choix.tris} tous={null} />
                    </Champ>
                    <p className="w-full text-xs text-gray-500">
                        Choisissez un commercial dans « Commercial » pour obtenir son audit complet. Les numéros sont comparés sur
                        leurs 8 derniers chiffres, les noms sans accents ni ordre des mots ; « Même nom » peut inclure de vrais
                        homonymes. Les lignes marquées d'un trait orange sont les re-saisies qui correspondent aux filtres.
                    </p>
                </div>

                <div className="flex gap-2">
                    <Button type="submit"><Search size={14} /> Filtrer</Button>
                    {actifs && <Button type="button" variant="ghost" onClick={reinitialiser}>Réinitialiser</Button>}
                </div>
            </form>

            {!modeDoublons && <TableauDeBord tdb={tableauDeBord} filters={actuels} filtrer={filtrer} />}

            {modeDoublons ? <Doublons doublons={doublons} filters={actuels} filtrer={filtrer} /> : <ListeClients clients={clients} />}
        </AppLayout>
    );
}

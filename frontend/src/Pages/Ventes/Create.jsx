import { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import { Upload, CreditCard, CheckCircle2 } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import { Card, CardBody } from '@/Components/ui/Card';
import { Input, Label, FieldError } from '@/Components/ui/Input';
import Button from '@/Components/ui/Button';
import { cn } from '@/lib/cn';

function Chip({ selected, onClick, children }) {
    return (
        <button
            type="button"
            onClick={onClick}
            className={cn(
                'rounded-lg border px-4 py-2.5 text-sm font-medium transition-colors',
                selected
                    ? 'border-gda-orange bg-orange-50 text-gda-orange'
                    : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300',
            )}
        >
            {children}
        </button>
    );
}

function Blocked({ title, children, actionHref, actionLabel }) {
    return (
        <Card>
            <CardBody className="text-center">
                <p className="font-medium text-gray-900">{title}</p>
                <p className="mt-1 text-sm text-gray-500">{children}</p>
                <div className="mt-4 flex justify-center gap-2">
                    {actionHref && <Button href={actionHref}>{actionLabel}</Button>}
                    <Button href={route('dashboard')} variant="outline">Retour au dashboard</Button>
                </div>
            </CardBody>
        </Card>
    );
}

export default function VentesCreate({ typesCartes, campagnesOuvertes, peutVendre, contratAccepte }) {
    const [form, setForm] = useState({
        campagne_id: campagnesOuvertes.length === 1 ? campagnesOuvertes[0].id : '',
        type_carte_id: '',
        prenom: '',
        nom: '',
        telephone: '',
        ville: '',
        quartier: '',
        carte_identite: null,
    });
    const [errors, setErrors] = useState({});
    const [submitting, setSubmitting] = useState(false);
    const [fileName, setFileName] = useState('');

    function set(key, value) {
        setForm((f) => ({ ...f, [key]: value }));
    }

    async function submit(e) {
        e.preventDefault();
        setSubmitting(true);
        setErrors({});

        const fd = new FormData();
        Object.entries(form).forEach(([k, v]) => {
            if (v !== null && v !== '') fd.append(k, v);
        });

        try {
            await window.axios.post('/api/ventes', fd, {
                headers: { Accept: 'application/json' },
            });
            router.visit(route('dashboard'));
        } catch (err) {
            const res = err.response?.data;
            if (res?.errors) {
                const flat = {};
                Object.entries(res.errors).forEach(([k, v]) => { flat[k] = Array.isArray(v) ? v[0] : v; });
                setErrors(flat);
            } else {
                setErrors({ _general: res?.message || 'Erreur lors de l\'enregistrement.' });
            }
        } finally {
            setSubmitting(false);
        }
    }

    return (
        <AppLayout title="Nouvelle vente" subtitle="Enregistrer une vente terrain">
            <Head title="Nouvelle vente" />

            {typesCartes.length === 0 ? (
                <Blocked title="Aucun type de carte actif">Contactez l'administrateur pour activer au moins un type de carte.</Blocked>
            ) : !peutVendre ? (
                <Blocked title="Aucune campagne active">Il n'y a pas de campagne ouverte pour votre agence en ce moment.</Blocked>
            ) : !contratAccepte ? (
                <Blocked title="Contrat non accepté" actionHref={route('commercial.contrat')} actionLabel="Voir mon contrat">
                    Vous devez accepter le contrat de prestation de la campagne en cours avant de pouvoir enregistrer une vente.
                </Blocked>
            ) : (
                <Card className="mx-auto max-w-xl">
                    <CardBody>
                        {campagnesOuvertes.length === 1 && (
                            <div className="mb-5 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-3.5 py-2.5 text-sm text-green-800">
                                <CheckCircle2 size={16} />
                                Vente rattachée à <strong>{campagnesOuvertes[0].nom}</strong> (fin le {campagnesOuvertes[0].date_fin})
                            </div>
                        )}

                        {errors._general && (
                            <div className="mb-5 rounded-lg border border-red-200 bg-red-50 px-3.5 py-2.5 text-sm text-red-800">
                                {errors._general}
                            </div>
                        )}

                        <form onSubmit={submit} className="space-y-5">
                            {campagnesOuvertes.length > 1 && (
                                <div>
                                    <Label>Campagne *</Label>
                                    <div className="flex flex-wrap gap-2">
                                        {campagnesOuvertes.map((c) => (
                                            <Chip key={c.id} selected={form.campagne_id === c.id} onClick={() => set('campagne_id', c.id)}>
                                                {c.nom} — fin {c.date_fin}
                                            </Chip>
                                        ))}
                                    </div>
                                    <FieldError>{errors.campagne_id}</FieldError>
                                </div>
                            )}

                            <div>
                                <Label>Type de carte *</Label>
                                <div className="flex flex-wrap gap-2">
                                    {typesCartes.map((t) => (
                                        <Chip key={t.id} selected={form.type_carte_id === t.id} onClick={() => set('type_carte_id', t.id)}>
                                            <span className="flex items-center gap-1.5"><CreditCard size={14} /> {t.code}</span>
                                        </Chip>
                                    ))}
                                </div>
                                <FieldError>{errors.type_carte_id}</FieldError>
                            </div>

                            <div className="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <Label htmlFor="prenom">Prénom *</Label>
                                    <Input id="prenom" value={form.prenom} onChange={(e) => set('prenom', e.target.value)} error={errors.prenom} required />
                                    <FieldError>{errors.prenom}</FieldError>
                                </div>
                                <div>
                                    <Label htmlFor="nom">Nom *</Label>
                                    <Input id="nom" value={form.nom} onChange={(e) => set('nom', e.target.value)} error={errors.nom} required />
                                    <FieldError>{errors.nom}</FieldError>
                                </div>
                            </div>

                            <div>
                                <Label htmlFor="telephone">Téléphone</Label>
                                <Input id="telephone" type="tel" value={form.telephone} onChange={(e) => set('telephone', e.target.value)} error={errors.telephone} />
                                <FieldError>{errors.telephone}</FieldError>
                            </div>

                            <div>
                                <Label htmlFor="carte_identite">Pièce d'identité <span className="font-normal text-gray-400">(image ou PDF)</span></Label>
                                <label
                                    htmlFor="carte_identite"
                                    className="flex cursor-pointer items-center justify-center gap-2 rounded-lg border-2 border-dashed border-gray-300 px-4 py-6 text-sm text-gray-500 hover:border-gda-orange hover:text-gda-orange"
                                >
                                    <Upload size={16} />
                                    {fileName || 'Choisir un fichier (JPG, PNG, GIF, WebP, PDF — max 10 Mo)'}
                                </label>
                                <input
                                    id="carte_identite"
                                    type="file"
                                    className="hidden"
                                    accept="image/jpeg,image/png,image/gif,image/webp,.pdf,application/pdf"
                                    onChange={(e) => {
                                        const f = e.target.files?.[0] ?? null;
                                        set('carte_identite', f);
                                        setFileName(f?.name ?? '');
                                    }}
                                />
                                <FieldError>{errors.carte_identite}</FieldError>
                            </div>

                            <div className="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <Label htmlFor="ville">Ville</Label>
                                    <Input id="ville" value={form.ville} onChange={(e) => set('ville', e.target.value)} error={errors.ville} />
                                </div>
                                <div>
                                    <Label htmlFor="quartier">Quartier</Label>
                                    <Input id="quartier" value={form.quartier} onChange={(e) => set('quartier', e.target.value)} error={errors.quartier} />
                                </div>
                            </div>

                            <div className="flex flex-col gap-2 pt-2">
                                <Button type="submit" size="lg" disabled={submitting}>
                                    {submitting ? 'Enregistrement…' : 'Valider la vente'}
                                </Button>
                                <Button href={route('dashboard')} variant="outline">Retour au dashboard</Button>
                            </div>
                        </form>
                    </CardBody>
                </Card>
            )}
        </AppLayout>
    );
}

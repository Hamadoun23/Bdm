import { useState } from 'react';
import { cn } from '@/lib/cn';

/**
 * Numéro de téléphone avec indicatif obligatoire, +223 (Mali) par défaut.
 * La valeur transmise au formulaire est complète : « +22376123456 ».
 * Les mêmes indicatifs et longueurs sont contrôlés côté serveur
 * (backend/terrain/telephones.py).
 */
export const INDICATIFS = [
    { code: '223', pays: 'Mali', longueur: 8 },
    { code: '221', pays: 'Sénégal', longueur: 9 },
    { code: '222', pays: 'Mauritanie', longueur: 8 },
    { code: '224', pays: 'Guinée', longueur: 9 },
    { code: '225', pays: "Côte d'Ivoire", longueur: 10 },
    { code: '226', pays: 'Burkina Faso', longueur: 8 },
    { code: '227', pays: 'Niger', longueur: 8 },
    { code: '228', pays: 'Togo', longueur: 8 },
    { code: '229', pays: 'Bénin', longueur: 10 },
    { code: '233', pays: 'Ghana', longueur: 9 },
    { code: '234', pays: 'Nigeria', longueur: 10 },
    { code: '237', pays: 'Cameroun', longueur: 9 },
    { code: '213', pays: 'Algérie', longueur: 9 },
    { code: '212', pays: 'Maroc', longueur: 9 },
    { code: '33', pays: 'France', longueur: 9 },
];

const PAR_DEFAUT = '223';

/** Sépare une valeur existante en indicatif + numéro national. */
export function decouper(valeur) {
    const brut = String(valeur ?? '').trim();
    let chiffres = brut.replace(/\D/g, '');
    if (brut.startsWith('+') || brut.startsWith('00')) {
        if (brut.startsWith('00')) chiffres = chiffres.slice(2);
        const connu = [...INDICATIFS]
            .sort((a, b) => b.code.length - a.code.length)
            .find((i) => chiffres.startsWith(i.code));
        if (connu) return { indicatif: connu.code, numero: chiffres.slice(connu.code.length) };
    }
    // Ancienne fiche saisie sans indicatif : on la suppose malienne.
    return { indicatif: PAR_DEFAUT, numero: chiffres };
}

export function TelephoneInput({ id, value, onChange, error, readOnly = false, className }) {
    const [parties, setParties] = useState(() => decouper(value));
    const pays = INDICATIFS.find((i) => i.code === parties.indicatif);

    function changer(nouvelles) {
        const suivantes = { ...parties, ...nouvelles };
        setParties(suivantes);
        onChange(suivantes.numero ? `+${suivantes.indicatif}${suivantes.numero}` : '');
    }

    const bordure = error ? 'border-red-300 focus:border-red-400' : 'border-gray-300 focus:border-gda-orange';

    return (
        <div className={className}>
            <div className="flex gap-2">
                <select
                    aria-label="Indicatif du pays"
                    value={parties.indicatif}
                    disabled={readOnly}
                    onChange={(e) => changer({ indicatif: e.target.value })}
                    className={cn(
                        'w-32 shrink-0 rounded-lg border bg-white px-2.5 py-2.5 text-sm text-gray-900 shadow-sm',
                        'focus:outline-none focus:ring-2 focus:ring-gda-orange/30',
                        bordure,
                    )}
                >
                    {INDICATIFS.map((i) => (
                        <option key={i.code} value={i.code}>+{i.code} {i.pays}</option>
                    ))}
                </select>
                <input
                    id={id}
                    type="tel"
                    inputMode="numeric"
                    autoComplete="tel-national"
                    value={parties.numero}
                    readOnly={readOnly}
                    maxLength={pays?.longueur ?? 12}
                    placeholder={'7'.padEnd(pays?.longueur ?? 8, '6')}
                    onChange={(e) => changer({ numero: e.target.value.replace(/\D/g, '') })}
                    className={cn(
                        'block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm',
                        'placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-gda-orange/30',
                        bordure,
                    )}
                />
            </div>
            <p className="mt-1 text-xs text-gray-500">
                {pays ? `${pays.longueur} chiffres après +${pays.code}` : ''}
                {pays && parties.numero && parties.numero.length !== pays.longueur && (
                    <span className="text-amber-700"> · {parties.numero.length} saisi(s)</span>
                )}
            </p>
        </div>
    );
}

export default TelephoneInput;

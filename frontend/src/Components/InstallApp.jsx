import { useState } from 'react';
import { Download, X } from 'lucide-react';
import Modal from './ui/Modal';
import Button from './ui/Button';
import useInstallPrompt from '@/hooks/useInstallPrompt';

const CLE_BANNIERE_MASQUEE = 'bdm_install_banniere_masquee';

function InstructionsInstallation({ open, onClose, iOS }) {
    return (
        <Modal
            open={open}
            onClose={onClose}
            title="Installer l'application"
            description="Un raccourci direct depuis l'écran d'accueil, sans passer par le navigateur."
        >
            {iOS ? (
                <ol className="list-decimal space-y-2 pl-4 text-sm text-gray-700">
                    <li>
                        Appuyez sur l'icône de <strong>partage</strong> (le carré avec une flèche vers le
                        haut) en bas de l'écran Safari.
                    </li>
                    <li>
                        Faites défiler le menu et choisissez <strong>« Sur l'écran d'accueil »</strong>.
                    </li>
                    <li>
                        Appuyez sur <strong>« Ajouter »</strong> en haut à droite.
                    </li>
                </ol>
            ) : (
                <ol className="list-decimal space-y-2 pl-4 text-sm text-gray-700">
                    <li>Ouvrez le menu du navigateur (les trois points, en haut à droite).</li>
                    <li>
                        Choisissez <strong>« Installer l'application »</strong> (ou{' '}
                        <strong>« Ajouter à l'écran d'accueil »</strong>).
                    </li>
                    <li>Confirmez l'installation.</li>
                </ol>
            )}
        </Modal>
    );
}

/**
 * Bouton compact pour l'en-tête : toujours accessible, discret, disparaît une
 * fois l'application installée. Sur un navigateur sans voie d'installation
 * fiable (Firefox desktop, par ex.), il n'y a rien de vrai à proposer.
 */
export function InstallAppButton() {
    const { installee, peutInstallerNatif, iOS, installer } = useInstallPrompt();
    const [showInstructions, setShowInstructions] = useState(false);

    if (installee || (!peutInstallerNatif && !iOS)) return null;

    async function onClick() {
        if (peutInstallerNatif) {
            await installer();
            return;
        }
        setShowInstructions(true);
    }

    return (
        <>
            <button
                onClick={onClick}
                title="Installer l'application"
                className="flex h-9 w-9 items-center justify-center rounded-full bg-white text-gray-400 shadow-sm ring-1 ring-gray-200 hover:text-gda-orange"
            >
                <Download size={16} />
            </button>
            <InstructionsInstallation open={showInstructions} onClose={() => setShowInstructions(false)} iOS={iOS} />
        </>
    );
}

/**
 * Bannière plus visible sur le dashboard, refermable (le choix reste en
 * mémoire sur cet appareil). Même logique de disponibilité que le bouton.
 */
export function InstallAppBanner() {
    const { installee, peutInstallerNatif, iOS, installer } = useInstallPrompt();
    const [showInstructions, setShowInstructions] = useState(false);
    const [masquee, setMasquee] = useState(() => {
        try {
            return localStorage.getItem(CLE_BANNIERE_MASQUEE) === '1';
        } catch {
            return false;
        }
    });

    if (installee || masquee || (!peutInstallerNatif && !iOS)) return null;

    function fermer() {
        setMasquee(true);
        try {
            localStorage.setItem(CLE_BANNIERE_MASQUEE, '1');
        } catch {
            // Stockage indisponible (navigation privée...) : la bannière
            // réapparaîtra à la prochaine visite, sans conséquence grave.
        }
    }

    async function onClick() {
        if (peutInstallerNatif) {
            await installer();
            return;
        }
        setShowInstructions(true);
    }

    return (
        <div className="mb-6 flex items-center justify-between gap-3 rounded-lg border border-orange-200 bg-orange-50 px-4 py-3 text-sm text-orange-900">
            <span className="flex items-center gap-2">
                <Download size={16} className="shrink-0" />
                Installez l'application sur votre écran d'accueil pour un accès plus rapide.
            </span>
            <div className="flex shrink-0 items-center gap-2">
                <Button onClick={onClick} size="sm">Installer</Button>
                <button onClick={fermer} title="Ne plus afficher" className="text-orange-400 hover:text-orange-600">
                    <X size={16} />
                </button>
            </div>
            <InstructionsInstallation open={showInstructions} onClose={() => setShowInstructions(false)} iOS={iOS} />
        </div>
    );
}

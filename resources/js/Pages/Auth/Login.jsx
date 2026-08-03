import { Head, useForm } from '@inertiajs/react';
import { LogIn, ShieldCheck } from 'lucide-react';
import { Input, PasswordInput, Label, FieldError } from '@/Components/ui/Input';
import Button from '@/Components/ui/Button';

export default function Login({ status }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    function submit(e) {
        e.preventDefault();
        post(route('login'));
    }

    return (
        <div className="flex min-h-screen">
            <Head title="Connexion" />

            {/* Panneau de marque */}
            <div className="relative hidden w-1/2 overflow-hidden bg-gradient-to-br from-gray-950 via-gray-900 to-black lg:flex lg:flex-col lg:justify-between lg:p-12">
                <div className="pointer-events-none absolute -right-20 -top-20 h-96 w-96 rounded-full bg-gda-orange/20 blur-3xl" />
                <div className="pointer-events-none absolute bottom-0 left-0 h-72 w-72 rounded-full bg-gda-orange/10 blur-3xl" />

                <div className="relative flex items-center gap-3">
                    <img src="/logo/gdamoney.png" alt="Gda Money" className="h-10 w-10 rounded-lg" />
                    <span className="font-brand text-lg font-semibold text-white">Campagne BDM</span>
                </div>

                <div className="relative max-w-md">
                    <h1 className="text-3xl font-semibold leading-tight text-white">
                        Pilotez vos campagnes de vente, en temps réel.
                    </h1>
                    <p className="mt-4 text-sm leading-relaxed text-white/70">
                        Ventes terrain, reporting téléphonique, performances et contrats — tout le suivi commercial
                        du Groupe GDA dans un seul espace.
                    </p>
                    <div className="mt-8 flex items-center gap-2 text-sm text-white/60">
                        <ShieldCheck size={16} />
                        Connexion sécurisée
                    </div>
                </div>

                <p className="relative text-xs text-white/40">© {new Date().getFullYear()} Groupe GDA</p>
            </div>

            {/* Formulaire */}
            <div
                className="flex w-full flex-col items-center justify-center bg-gray-50 px-6 py-12 lg:w-1/2"
                style={{ paddingTop: 'calc(env(safe-area-inset-top) + 3rem)', paddingBottom: 'calc(env(safe-area-inset-bottom) + 3rem)' }}
            >
                <div className="w-full max-w-sm">
                    <div className="mb-8 text-center lg:hidden">
                        <img src="/logo/gdamoney.png" alt="Gda Money" className="mx-auto mb-3 h-14 w-14 rounded-xl" />
                        <h1 className="font-brand text-xl font-semibold text-gray-900">Campagne BDM</h1>
                    </div>

                    <h2 className="text-xl font-semibold text-gray-900">Connexion</h2>
                    <p className="mt-1 text-sm text-gray-500">Accédez à votre espace GDA Money.</p>

                    {status && (
                        <div className="mt-5 rounded-lg border border-blue-200 bg-blue-50 px-3.5 py-2.5 text-sm text-blue-800">
                            {status}
                        </div>
                    )}

                    <form onSubmit={submit} className="mt-6 space-y-4">
                        <div>
                            <Label htmlFor="email">Identifiant</Label>
                            <Input
                                id="email"
                                type="text"
                                autoFocus
                                autoComplete="username"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                error={errors.email}
                                placeholder="Email, téléphone ou nom"
                            />
                            <FieldError>{errors.email}</FieldError>
                        </div>

                        <div>
                            <Label htmlFor="password">Mot de passe</Label>
                            <PasswordInput
                                id="password"
                                autoComplete="current-password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                error={errors.password}
                                placeholder="••••••••"
                            />
                            <FieldError>{errors.password}</FieldError>
                        </div>

                        <label className="flex items-center gap-2 text-sm text-gray-600">
                            <input
                                type="checkbox"
                                checked={data.remember}
                                onChange={(e) => setData('remember', e.target.checked)}
                                className="h-4 w-4 rounded border-gray-300 text-gda-orange focus:ring-gda-orange/40"
                            />
                            Rester connecté
                        </label>

                        <Button type="submit" disabled={processing} className="w-full" size="lg">
                            <LogIn size={16} />
                            Se connecter
                        </Button>
                    </form>
                </div>
            </div>
        </div>
    );
}

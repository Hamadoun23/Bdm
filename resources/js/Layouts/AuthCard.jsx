export default function AuthCard({ title, subtitle, children }) {
    return (
        <div className="flex min-h-screen items-center justify-center bg-gray-50 px-6 py-12">
            <div className="w-full max-w-sm">
                <div className="mb-8 text-center">
                    <img src="/logo/gdamoney.png" alt="Gda Money" className="mx-auto mb-3 h-14 w-14 rounded-xl" />
                    <h1 className="font-brand text-xl font-semibold text-gray-900">Campagne BDM</h1>
                </div>

                <h2 className="text-xl font-semibold text-gray-900">{title}</h2>
                {subtitle && <p className="mt-1 text-sm text-gray-500">{subtitle}</p>}

                <div className="mt-6">{children}</div>
            </div>
        </div>
    );
}
